# -*- coding: utf-8 -*-
import os
import cv2
import numpy as np
import mediapipe as mp
import pickle
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split

DATASET_PATH = "D:/archive/asl_alphabet_train/asl_alphabet_train"
SAMPLES_PER_CLASS = 300

mp_hands = mp.solutions.hands
hands = mp_hands.Hands(static_image_mode=True, max_num_hands=1, min_detection_confidence=0.3)

data = []
labels = []

letters = sorted([l for l in os.listdir(DATASET_PATH) if len(l) == 1 and l.isalpha()])
print(f"Training on {len(letters)} classes...")

for letter in letters:
    folder = os.path.join(DATASET_PATH, letter)
    images = os.listdir(folder)[:SAMPLES_PER_CLASS]
    count = 0
    for img_file in images:
        try:
            img = cv2.imread(os.path.join(folder, img_file))
            rgb = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
            result = hands.process(rgb)
            if result.multi_hand_landmarks:
                lm = result.multi_hand_landmarks[0].landmark
                wrist_x, wrist_y, wrist_z = lm[0].x, lm[0].y, lm[0].z
                features = []
                for point in lm:
                    features.extend([point.x-wrist_x, point.y-wrist_y, point.z-wrist_z])
                data.append(features)
                labels.append(letter)
                count += 1
        except:
            pass
    print(f"{letter}: {count} samples")

print(f"\nTotal: {len(data)} samples. Training...")

X_train, X_test, y_train, y_test = train_test_split(np.array(data), np.array(labels), test_size=0.2, random_state=42)
model = RandomForestClassifier(n_estimators=200, random_state=42, n_jobs=-1)
model.fit(X_train, y_train)

print(f"Accuracy: {model.score(X_test, y_test)*100:.1f}%")

with open('model.pkl', 'wb') as f:
    pickle.dump(model, f)
print("Model saved!")