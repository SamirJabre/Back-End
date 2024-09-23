# Import required libraries
import pandas as pd
from sklearn.preprocessing import LabelEncoder
from sklearn.model_selection import train_test_split

# Step 1: Load the dataset and inspect it
# Load your CSV file into a DataFrame (replace 'historical_trips.csv' with your actual file path)
data = pd.read_csv('trips_data.csv')

# Inspect the first few rows of the dataset
print("Data Preview:")
print(data.head())  # Shows the first 5 rows

# Check for missing values in the dataset
print("\nMissing Values:")
print(data.isnull().sum())  # Check if any columns have missing values

# Display summary information about the dataset (columns, data types, memory usage)
print("\nData Info:")
print(data.info())  # Gives an overview of the dataset's structure

# Check basic statistics (mean, std, min, max) for numerical columns
print("\nBasic Statistics:")
print(data.describe())  # Provides basic statistics for numerical columns

# Print the shape of the dataset (number of rows, number of columns)
print("\nDataset Shape:")
print(data.shape)  # Prints (number of rows, number of columns)

# Step 2: Feature Engineering

# Step 1: Encode 'weather_condition' (sunny, clear, overcast, rainy) using LabelEncoder
label_encoder = LabelEncoder()
data['weather_condition'] = label_encoder.fit_transform(data['weather_condition'])

# Print the mapping of encoded weather conditions
print("\nEncoded Weather Conditions Mapping:")
print(dict(zip(label_encoder.classes_, label_encoder.transform(label_encoder.classes_))))

# Step 2: Convert 'departure_time' and 'arrival_time' to seconds since midnight
# Convert 'departure_time' and 'arrival_time' to datetime format
data['departure_time'] = pd.to_datetime(data['departure_time'], format='%H:%M:%S')
data['arrival_time'] = pd.to_datetime(data['arrival_time'], format='%H:%M:%S')

# Convert 'departure_time' and 'arrival_time' to seconds since midnight
data['departure_seconds'] = data['departure_time'].dt.hour * 3600 + data['departure_time'].dt.minute * 60 + data['departure_time'].dt.second
data['arrival_seconds'] = data['arrival_time'].dt.hour * 3600 + data['arrival_time'].dt.minute * 60 + data['arrival_time'].dt.second

# Step 3: Drop the original 'departure_time' and 'arrival_time' columns
data = data.drop(['departure_time', 'arrival_time'], axis=1)

# Print the updated data to confirm the changes
print("\nUpdated Data Preview:")
print(data.head())

# Optionally save the updated data to a new CSV file for future use
data.to_csv('processed_trips_data.csv', index=False)

# Step 4: Preparing Data for Model Training

# Define features (X) and target (y)
# To predict traffic_level
# X = data.drop(['traffic_level', 'arrival_seconds'], axis=1)  # Features excluding the target columns
# y = data['traffic_level']  # Target column for traffic prediction

# To predict arrival time
X = data.drop(['arrival_seconds', 'traffic_level'], axis=1)  # Features excluding the target columns
y = data['arrival_seconds']  # Target column for arrival time prediction

# Split the data into training and testing sets (80% training, 20% testing)
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Print the shapes of the training and testing sets to confirm the split
print("Training set shape:", X_train.shape)
print("Testing set shape:", X_test.shape)
