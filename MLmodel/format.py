import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_absolute_error, mean_squared_error
import datetime
import joblib

# Step 1: Load and Explore the Data

# Load the CSV file into a DataFrame
df = pd.read_csv('trips_data.csv')

# Display the first few rows of the DataFrame
print("First few rows of the dataset:")
print(df.head())

# Display summary statistics of the DataFrame
print("\nSummary statistics of the dataset:")
print(df.describe())

# Display information about the DataFrame
print("\nInformation about the dataset:")
print(df.info())

# Check for missing values
print("\nMissing values in the dataset:")
print(df.isnull().sum())

# Step 2: Preprocess the Data

# Convert time columns to datetime
df['departure_time'] = pd.to_datetime(df['departure_time'], format='%H:%M:%S').dt.time
df['arrival_time'] = pd.to_datetime(df['arrival_time'], format='%H:%M:%S').dt.time

# Convert arrival_time to seconds since midnight
df['arrival_seconds'] = df['arrival_time'].apply(lambda x: x.hour * 3600 + x.minute * 60 + x.second)

# Extract features from datetime columns
df['departure_hour'] = pd.to_datetime(df['departure_time'], format='%H:%M:%S').dt.hour
df['departure_minute'] = pd.to_datetime(df['departure_time'], format='%H:%M:%S').dt.minute

# Encode categorical variables
df = pd.get_dummies(df, columns=['from', 'to', 'traffic_level', 'weather_condition', 'day'])

# Step 3: Feature Selection

# Select 'arrival_seconds' as the target (y) before dropping it from the DataFrame
y = df['arrival_seconds']  # Target is 'arrival_seconds'

# Now drop unnecessary columns
df = df.drop(columns=['trip_id', 'date', 'arrival_time', 'duration', 'arrival_seconds'])

# Dynamically print column names to understand the one-hot encoded column names
print("\nColumn names after encoding:")
print(df.columns)

# Select relevant features based on actual column names
features = [
    'departure_hour', 'departure_minute', 'passengers', 'avg_speed',
    # Add dynamically based on actual column names
] + [col for col in df.columns if col.startswith('from_') or col.startswith('to_') or col.startswith('traffic_level_') or col.startswith('weather_condition_') or col.startswith('day_')]

# Extract features (X)
X = df[features]

# Display the first few rows of the features and target
print("\nFirst few rows of the features (X):")
print(X.head())

print("\nFirst few rows of the target (y):")
print(y.head())

# Step 4: Split the Data

# Split the data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Display the shapes of the training and testing sets
print("\nShapes of the training and testing sets:")
print(f"X_train: {X_train.shape}, y_train: {y_train.shape}")
print(f"X_test: {X_test.shape}, y_test: {y_test.shape}")

# Step 5: Train a Model

# Initialize the model
model = RandomForestRegressor(n_estimators=100, random_state=42)

# Train the model
model.fit(X_train, y_train)

# Display a message indicating that the model has been trained
print("\nModel has been trained.")

# Step 6: Evaluate the Model

# Make predictions on the testing set
y_pred = model.predict(X_test)

# Calculate performance metrics
mae = mean_absolute_error(y_test, y_pred)
mse = mean_squared_error(y_test, y_pred)

# Display the performance metrics
print("\nModel Evaluation Metrics:")
print(f"Mean Absolute Error (MAE): {mae}")
print(f"Mean Squared Error (MSE): {mse}")

# Step 7: Make Predictions

# Example new data (replace with actual new data)
new_data = pd.DataFrame({
    'departure_hour': [15],
    'departure_minute': [0],
    'from_Tripoli': [1] ,
    'to_Batroun': [1],      # Example locations
    'traffic_level_1': [1], 
    'weather_condition_clear': [1],
    'day_monday': [1]
})

# Ensure the new data has the same columns as the training data
new_data = new_data.reindex(columns=features, fill_value=0)

# Make prediction
predicted_arrival_seconds = model.predict(new_data)

# Convert predicted seconds back to time format
predicted_arrival_time = [str(datetime.timedelta(seconds=int(sec))) for sec in predicted_arrival_seconds]

# Display the predicted arrival time
print("\nPredicted Arrival Time:")
print(predicted_arrival_time)
joblib.dump(model, 'arrival_time_predictor.pkl')