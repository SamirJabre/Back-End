from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import Optional
import pandas as pd
import datetime
from sklearn.ensemble import RandomForestRegressor
from sklearn.model_selection import train_test_split
from sklearn.metrics import mean_absolute_error, mean_squared_error

app = FastAPI()

# Example: Pretrained RandomForestRegressor model (You should load your actual trained model)
model = RandomForestRegressor(n_estimators=100, random_state=42)

# Dummy training data (for demonstration purposes)
dummy_data = {
    "departure_hour": [8, 9, 10],
    "departure_minute": [0, 15, 30],
    "from_Tripoli": [1, 0, 0],
    "to_Batroun": [0, 1, 1],
    "traffic_level_1": [1, 0, 1],
    "weather_condition_clear": [1, 1, 0],
    "day_monday": [1, 0, 1]
}
df_dummy = pd.DataFrame(dummy_data)

# Simulate the 'arrival_seconds' column (for demo purposes)
df_dummy['arrival_seconds'] = [3600, 7200, 5400]

# Split the dummy data (again, for demo purposes)
X = df_dummy.drop(columns=['arrival_seconds'])
y = df_dummy['arrival_seconds']
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train the model (in reality, you should load a pre-trained model instead of retraining every time)
model.fit(X_train, y_train)

# Define the request model using Pydantic with optional fields
class PredictionRequest(BaseModel):
    departure_hour: int
    departure_minute: int
    from_Tripoli: Optional[int] = 0
    from_Anfeh: Optional[int] = 0
    from_Chekka: Optional[int] = 0
    from_Batroun: Optional[int] = 0
    from_Jbeil: Optional[int] = 0
    from_Tabarja: Optional[int] = 0
    from_Jounieh: Optional[int] = 0
    from_Antelias: Optional[int] = 0
    from_Beirut: Optional[int] = 0
    to_Tripoli: Optional[int] = 0
    to_Anfeh: Optional[int] = 0
    to_Chekka: Optional[int] = 0
    to_Batroun: Optional[int] = 0
    to_Jbeil: Optional[int] = 0
    to_Tabarja: Optional[int] = 0
    to_Jounieh: Optional[int] = 0
    to_Antelias: Optional[int] = 0
    to_Beirut: Optional[int] = 0
    traffic_level_1: Optional[int] = 0
    weather_condition_clear: Optional[int] = 0
    day_monday: Optional[int] = 0
    day_tuesday: Optional[int] = 0
    day_wednesday: Optional[int] = 0
    day_thursday: Optional[int] = 0
    day_friday: Optional[int] = 0
    day_saturday: Optional[int] = 0
    day_sunday: Optional[int] = 0

@app.post("/predict")
async def predict(request: PredictionRequest):
    try:
        # Prepare the input data for prediction
        input_data = pd.DataFrame([{
            "departure_hour": request.departure_hour,
            "departure_minute": request.departure_minute,
            "from_Tripoli": request.from_Tripoli,
            "from_Anfeh": request.from_Anfeh,
            "from_Chekka": request.from_Chekka,
            "from_Batroun": request.from_Batroun,
            "from_Jbeil": request.from_Jbeil,
            "from_Tabarja": request.from_Tabarja,
            "from_Jounieh": request.from_Jounieh,
            "from_Antelias": request.from_Antelias,
            "from_Beirut": request.from_Beirut,
            "to_Tripoli": request.to_Tripoli,
            "to_Anfeh": request.to_Anfeh,
            "to_Chekka": request.to_Chekka,
            "to_Batroun": request.to_Batroun,
            "to_Jbeil": request.to_Jbeil,
            "to_Tabarja": request.to_Tabarja,
            "to_Jounieh": request.to_Jounieh,
            "to_Antelias": request.to_Antelias,
            "to_Beirut": request.to_Beirut,
            "traffic_level_1": request.traffic_level_1,
            "weather_condition_clear": request.weather_condition_clear,
            "day_monday": request.day_monday,
            "day_tuesday": request.day_tuesday,
            "day_wednesday": request.day_wednesday,
            "day_thursday": request.day_thursday,
            "day_friday": request.day_friday,
            "day_saturday": request.day_saturday,
            "day_sunday": request.day_sunday
        }])

        # Ensure the input data columns match the training data
        input_data = input_data.reindex(columns=X_train.columns, fill_value=0)

        # Make prediction
        predicted_arrival_seconds = model.predict(input_data)

        # Convert predicted seconds back to time format
        predicted_arrival_time = str(datetime.timedelta(seconds=int(predicted_arrival_seconds[0])))

        # Return the prediction result
        return {"predicted_arrival_time": predicted_arrival_time}

    except Exception as e:
        raise HTTPException(status_code=500, detail=f"An error occurred during prediction: {e}")

# Custom exception handler to return internal server error details
@app.exception_handler(Exception)
async def custom_exception_handler(request, exc):
    return JSONResponse(
        status_code=500,
        content={"message": f"Internal Server Error: {exc}"}
    )
