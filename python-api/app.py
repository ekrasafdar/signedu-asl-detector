# -*- coding: utf-8 -*-
from flask import Flask, request, jsonify
from flask_cors import CORS
import cv2
import mediapipe as mp
import numpy as np
import pickle
import random
import base64
import os
from PIL import Image
import io

app = Flask(__name__)
CORS(app)

DATASET_PATH = "D:/archive/asl_alphabet_train/asl_alphabet_train"

mp_hands = mp.solutions.hands
hands = mp_hands.Hands(static_image_mode=True, max_num_hands=1, min_detection_confidence=0.3)

with open('model.pkl', 'rb') as f:
    model = pickle.load(f)

print("Model loaded!")

asl_descriptions = {
    'A':'Closed fist with thumb to the side',
    'B':'Flat hand fingers together thumb tucked',
    'C':'Curved hand forming letter C',
    'D':'Index up other fingers curved touching thumb',
    'E':'Fingers bent thumb tucked under',
    'F':'Index and thumb circle other fingers up',
    'G':'Index points sideways thumb parallel',
    'H':'Index and middle finger point sideways',
    'I':'Pinky finger up fist closed',
    'J':'Pinky up draw J in air',
    'K':'Index and middle up thumb between them',
    'L':'L-shape with index and thumb',
    'M':'Three fingers over thumb',
    'N':'Two fingers over thumb',
    'O':'Fingers and thumb form O shape',
    'P':'K handshape pointing downward',
    'Q':'G handshape pointing downward',
    'R':'Index and middle fingers crossed',
    'S':'Closed fist thumb over fingers',
    'T':'Thumb between index and middle',
    'U':'Index and middle fingers together pointing up',
    'V':'Index and middle fingers spread peace sign',
    'W':'Three fingers spread wide',
    'X':'Index finger hooked',
    'Y':'Thumb and pinky extended',
    'Z':'Index finger traces Z in air'
}

def extract_features(landmarks):
    lm = landmarks.landmark
    # Normalize relative to wrist
    wrist_x = lm[0].x
    wrist_y = lm[0].y
    wrist_z = lm[0].z
    features = []
    for point in lm:
        features.extend([
            point.x - wrist_x,
            point.y - wrist_y,
            point.z - wrist_z
        ])
    return features

def get_sample_image(letter):
    try:
        letter_path = os.path.join(DATASET_PATH, letter.upper())
        if os.path.exists(letter_path):
            images = os.listdir(letter_path)
            if images:
                img_path = os.path.join(letter_path, random.choice(images[:20]))
                img = Image.open(img_path).resize((200, 200))
                buffer = io.BytesIO()
                img.save(buffer, format='JPEG')
                return base64.b64encode(buffer.getvalue()).decode()
    except:
        pass
    return None

@app.route('/detect', methods=['POST'])
def detect():
    try:
        file = request.files.get('image')
        if not file:
            return jsonify({'success': False, 'error': 'No image'})

        img_bytes = file.read()
        nparr = np.frombuffer(img_bytes, np.uint8)
        frame = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

        if frame is None:
            return jsonify({'success': False, 'error': 'Invalid image'})

        rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        results = hands.process(rgb)

        if results.multi_hand_landmarks:
            features = extract_features(results.multi_hand_landmarks[0])
            prediction = model.predict([features])[0]
            proba = model.predict_proba([features])[0]
            confidence = round(max(proba) * 100, 1)

            return jsonify({
                'success': True,
                'letter': prediction,
                'confidence': confidence,
                'description': asl_descriptions.get(prediction, ''),
                'hand_detected': True,
                'sample_image': get_sample_image(prediction)
            })

        return jsonify({
            'success': True,
            'letter': '?',
            'confidence': 0,
            'description': 'No hand detected! Show your hand clearly.',
            'hand_detected': False,
            'sample_image': None
        })

    except Exception as e:
        return jsonify({'success': False, 'error': str(e)})

@app.route('/explain', methods=['POST'])
def explain():
    data = request.json
    letter = data.get('letter', 'A').upper()
    return jsonify({
        'letter': letter,
        'explanation': f"ASL sign for '{letter}': {asl_descriptions.get(letter, '')}. Practice slowly.",
        'sample_image': get_sample_image(letter)
    })

@app.route('/sample/<letter>', methods=['GET'])
def sample(letter):
    return jsonify({'letter': letter.upper(), 'image': get_sample_image(letter.upper())})

@app.route('/health', methods=['GET'])
def health():
    return jsonify({'status': 'running', 'model': 'Random Forest 98.4%', 'dataset_loaded': os.path.exists(DATASET_PATH)})

if __name__ == '__main__':
    app.run(port=5000, debug=True)