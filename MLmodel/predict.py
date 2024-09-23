# Import required libraries
import pandas as pd
from sklearn.preprocessing import LabelEncoder
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier, RandomForestRegressor
from sklearn.metrics import classification_report, mean_squared_error

# Step 1: Load the dataset and inspect it
data = pd.read_csv('trips_data.csv')

# Inspect the first few rows of the dataset
print("Data Preview:")
print(data.head())  # Shows the first 5 rows

# Step 2: Feature Engineering

# Convert 'date' to datetime format and extract useful numerical features
data['date'] = pd.to_datetime(data['date'], format='%Y-%m-%d')
data['day_of_week'] = data['date'].dt.dayofweek  # Monday=0, Sunday=6
data['day'] = data['date'].dt.day                # Day of the month
data['month'] = data['date'].dt.month            # Month
data['year'] = data['date'].dt.year              # Year

# Drop the original 'date' column
data = data.drop(['date'], axis=1)

# Encode 'from' and 'to' columns using LabelEncoder
label_encoder_from = LabelEncoder()
data['from'] = label_encoder_from.fit_transform(data['from'])

label_encoder_to = LabelEncoder()
data['to'] = label_encoder_to.fit_transform(data['to'])

# Print the mapping for 'from' and 'to'
print("\nEncoded 'From' Mapping:")
print(dict(zip(label_encoder_from.classes_, label_encoder_from.transform(label_encoder_from.classes_))))
print("\nEncoded 'To' Mapping:")
print(dict(zip(label_encoder_to.classes_, label_encoder_to.transform(label_encoder_to.classes_))))

# Encode 'weather_condition' (sunny, clear, overcast, rainy) using LabelEncoder
label_encoder_weather = LabelEncoder()
data['weather_condition'] = label_encoder_weather.fit_transform(data['weather_condition'])

# Print the mapping of encoded weather conditions
print("\nEncoded Weather Conditions Mapping:")
print(dict(zip(label_encoder_weather.classes_, label_encoder_weather.transform(label_encoder_weather.classes_))))

# Convert 'departure_time' and 'arrival_time' to seconds since midnight
data['departure_time'] = pd.to_datetime(data['departure_time'], format='%H:%M:%S')
data['arrival_time'] = pd.to_datetime(data['arrival_time'], format='%H:%M:%S')

# Convert 'departure_time' and 'arrival_time' to seconds since midnight
data['departure_seconds'] = data['departure_time'].dt.hour * 3600 + data['departure_time'].dt.minute * 60 + data['departure_time'].dt.second
data['arrival_seconds'] = data['arrival_time'].dt.hour * 3600 + data['arrival_time'].dt.minute * 60 + data['arrival_time'].dt.second

# Drop the original 'departure_time' and 'arrival_time' columns
data = data.drop(['departure_time', 'arrival_time'], axis=1)

# Print the updated data to confirm the changes
print("\nUpdated Data Preview:")
print(data.head())

# Step 3: Preparing Data for Model Training

# Define features (X) and target (y) for predicting traffic_level
X = data.drop(['traffic_level', 'arrival_seconds'], axis=1)
y_traffic = data['traffic_level']

# Split the data into training and testing sets (80% training, 20% testing) for traffic_level prediction
X_train, X_test, y_train_traffic, y_test_traffic = train_test_split(X, y_traffic, test_size=0.2, random_state=42)

# Define features (X) and target (y) for predicting arrival_seconds
X_arrival = data.drop(['arrival_seconds', 'traffic_level'], axis=1)
y_arrival = data['arrival_seconds']

# Split the data into training and testing sets (80% training, 20% testing) for arrival_seconds prediction
X_train_arrival, X_test_arrival, y_train_arrival, y_test_arrival = train_test_split(X_arrival, y_arrival, test_size=0.2, random_state=42)

# Step 4: Train and Evaluate Models

# Initialize and train the RandomForestClassifier for traffic_level prediction
rf_classifier = RandomForestClassifier(n_estimators=100, random_state=42)
rf_classifier.fit(X_train, y_train_traffic)

# Predict traffic_level on the test set
y_pred_traffic = rf_classifier.predict(X_test)

# Evaluate the classifier
print("\nTraffic Level Prediction - Classification Report:")
print(classification_report(y_test_traffic, y_pred_traffic))

# Initialize and train the RandomForestRegressor for arrival_seconds prediction
rf_regressor = RandomForestRegressor(n_estimators=100, random_state=42)
rf_regressor.fit(X_train_arrival, y_train_arrival)

# Predict arrival_seconds on the test set
y_pred_arrival = rf_regressor.predict(X_test_arrival)

# Evaluate the regressor
mse_arrival = mean_squared_error(y_test_arrival, y_pred_arrival)
print("\nArrival Seconds Prediction - Mean Squared Error:")
print(mse_arrival)
