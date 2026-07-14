import cv2
import numpy as np
import sys
import os

def process_image(image_path):
    if not os.path.exists(image_path):
        print("ERROR: Image not found.")
        sys.exit(1)
        
    img = cv2.imread(image_path)
    if img is None:
        print("ERROR: Could not read image.")
        sys.exit(1)
        
    # 1. Convert to grayscale
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    
    # 2. Remove shadow and make background white
    # Adaptive thresholding is excellent for documents
    # Block size 41, C=15 works well for varying illumination
    thresh = cv2.adaptiveThreshold(gray, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, cv2.THRESH_BINARY, 41, 15)
    
    # Optional: Light morphological closing to connect broken text parts 
    # and remove tiny noise
    kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (2, 2))
    cleaned = cv2.morphologyEx(thresh, cv2.MORPH_CLOSE, kernel)
    
    # 3. Detect text/stamp presence
    # Invert the image so text/stamps are white (255) and background is black (0)
    inv = cv2.bitwise_not(cleaned)
    
    # Find contours
    contours, _ = cv2.findContours(inv, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    
    has_valid_content = False
    
    for c in contours:
        area = cv2.contourArea(c)
        # Filter out very small noise
        if area > 100:
            has_valid_content = True
            break
            
    if not has_valid_content:
        print("ERROR: No text or stamp detected.")
        sys.exit(2)
        
    # 4. Save the cleaned image back
    # Cleaned image is black text on white background (thresh image)
    cv2.imwrite(image_path, cleaned)
    print("SUCCESS")
    sys.exit(0)

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("ERROR: Missing image path argument.")
        sys.exit(1)
    process_image(sys.argv[1])
