import cv2
import numpy as np
import tensorflow as tf
from tensorflow.keras.models import load_model
import sys
import json
from flask import Flask, request, jsonify
import os

app = Flask(__name__)

@app.route('/predict', methods=['POST'])
def predict():
    try:
        # 从请求中获取上传的图像文件
        file = request.files['image']
        if file:
            # 保存上传的图像
            image_path = '/path/to/save/image.jpg'
            file.save(image_path)

            # 进行图像识别
            result = recognize_image(image_path)

            return jsonify(result)

    except Exception as e:
        return jsonify({"success": False, "error_message": str(e)})

def recognize_image(image_path):
    try:
        # 加载模型
        # 加载模型
        model = load_model('/usr/share/nginx/html/working/skin_model.h5')


        # 读取并预处理图像
        image = cv2.imread(image_path)
        if image is None:
            raise Exception("Failed to read the image.")

        image = cv2.resize(image, (224, 224))  # 调整大小
        image = np.expand_dims(image, axis=0)  # 添加批次维度

        # 进行推断
        predictions = model.predict(image)

        # 获取最终的分类结果
        predicted_class = np.argmax(predictions[0])
        class_labels = [
            '1. 濕疹',
            '2. 黑色素瘤', '3. 過敏性皮膚炎', '4. 基底細胞癌(BCC)',
            '5. 黑色素痣(NV)', '6. 良性角化樣病變(BKL)',
            '7. 銀屑病、扁平苔癬及相關疾病',
            '8. 脂漏性角化病、其他良性腫瘤',
            '9. 白癬、環形蟲菌病、念珠菌感染及其他真菌感染',
            '10. 疣、软疣、其他病毒感染'
        ]
        # class_labels = [
        #     '1. Eczema 1677',
        #     '2. Melanoma 15.75k', '3. Atopic Dermatitis - 1.25k', '4. Basal Cell Carcinoma (BCC) 3323',
        #     '5. Melanocytic Nevi (NV) - 7970', '6. Benign Keratosis-like Lesions (BKL) 2624',
        #     '7. Psoriasis pictures Lichen Planus and related diseases - 2k',
        #     '8. Seborrheic Keratoses and other Benign Tumors - 1.8k',
        #     '9. Tinea Ringworm Candidiasis and other Fungal Infections - 1.7k',
        #     '10. Warts Molluscum and other Viral Infections - 2103'
        # ]
        predicted_class_label = class_labels[predicted_class]
        
        return {"success": True, "predicted_class": predicted_class_label}

    except Exception as e:
        return {"success": False, "error_message": str(e)}


if __name__ == "__main__":
    app.run(debug=True)
