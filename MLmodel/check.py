# Import required libraries
import pandas as pd
import numpy as np

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
