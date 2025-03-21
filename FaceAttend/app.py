from flask import Flask, request, jsonify
import os
import numpy as np
import cv2
from keras.api.keras.applications import ResNet50
from keras.api.keras.applications.resnet50 import preprocess_input
from keras.api.keras.models import Model
from scipy.spatial.distance import cosine
from flask_cors import CORS  # Import thư viện CORS


app = Flask(__name__)
CORS(app)  # Cho phép tất cả các domain truy cập API

UPLOAD_FOLDER = 'data'
FEATURES_FILE = 'face_features.npy'

if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

# Load ResNet50 để trích xuất đặc trưng
base_model = ResNet50(weights='imagenet', include_top=False, pooling='avg')
model = Model(inputs=base_model.input, outputs=base_model.output)

# Hàm load ảnh và tiền xử lý
def load_and_preprocess_image(image_path):
    img = cv2.imread(image_path)
    img = cv2.resize(img, (224, 224))
    img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    img = preprocess_input(img)
    return img

@app.route('/upload-face', methods=['POST'])
def upload_face():
    msv = request.form.get('msv')
    if not msv:
        return jsonify({"error": "MSV không được để trống"}), 400

    folder_path = os.path.join(UPLOAD_FOLDER, msv)
    os.makedirs(folder_path, exist_ok=True)

    files = request.files.getlist('images[]')
    if not files:
        return jsonify({"error": "Không có ảnh nào được gửi"}), 400

    embeddings = []  # Danh sách đặc trưng khuôn mặt của sinh viên

    for file in files:
        file_path = os.path.join(folder_path, file.filename)
        file.save(file_path)

        # Trích xuất đặc trưng từ ảnh
        img = load_and_preprocess_image(file_path)
        img = np.expand_dims(img, axis=0)  # Thêm batch dimension
        feature = model.predict(img)[0]
        embeddings.append(feature)

    if embeddings:
        # Tính trung bình đặc trưng để ổn định nhận dạng
        face_embedding = np.mean(embeddings, axis=0)

        # Load file đặc trưng đã lưu (nếu có)
        if os.path.exists(FEATURES_FILE):
            face_embeddings = np.load(FEATURES_FILE, allow_pickle=True).item()
        else:
            face_embeddings = {}

        # Cập nhật đặc trưng của sinh viên
        face_embeddings[msv] = face_embedding

        # Lưu đặc trưng vào file
        np.save(FEATURES_FILE, face_embeddings)

    return jsonify({"message": f"Đã lưu {len(files)} ảnh & cập nhật đặc trưng cho {msv}"}), 200


@app.route('/predict-face', methods=['POST'])
def predict_face():
    if 'image' not in request.files:
        return jsonify({"error": "Không có ảnh nào được gửi"}), 400

    file = request.files['image']
    file_path = os.path.join(UPLOAD_FOLDER, "temp.jpg")
    file.save(file_path)

    # Trích xuất đặc trưng từ ảnh
    img = load_and_preprocess_image(file_path)
    img = np.expand_dims(img, axis=0)  # Thêm batch dimension
    query_feature = model.predict(img)[0]

    # Kiểm tra file đặc trưng
    if not os.path.exists(FEATURES_FILE):
        return jsonify({"error": "Chưa có dữ liệu sinh viên"}), 400

    # Load đặc trưng đã lưu
    face_embeddings = np.load(FEATURES_FILE, allow_pickle=True).item()

    # Tìm sinh viên có khoảng cách cosine nhỏ nhất
    best_match = None
    min_distance = float('inf')

    for msv, feature in face_embeddings.items():
        distance = cosine(query_feature, feature)
        if distance < min_distance:
            min_distance = distance
            best_match = msv

    if best_match is None or min_distance > 0.5:  # Ngưỡng 0.5 để nhận dạng
        return jsonify({"message": "Không tìm thấy sinh viên phù hợp"}), 404

    return jsonify({"message": "Nhận dạng thành công", "msv": best_match, "distance": min_distance}), 200

if __name__ == '__main__':
    app.run(debug=True)
