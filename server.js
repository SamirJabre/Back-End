const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const axios = require('axios');

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
  cors: {
    origin: '*',
    methods: ['GET', 'POST'],
  },
});

const LARAVEL_API_URL = 'http://192.168.1.108:8000/api/update-location'; // Laravel API URL

io.on('connection', (socket) => {
  console.log('A user connected');

  socket.on('updateLocation', (data) => {
    console.log('Received location update:', data);

    // Make HTTP request to Laravel backend
    axios.post(LARAVEL_API_URL, {
      busId: data.busId,
      current_location: data.current_location,
    })
    .then(response => {
      console.log('Location updated in Laravel:', response.data);
    })
    .catch(error => {
      console.error('Error updating location in Laravel:', error.response.data);
    });
  });

  socket.on('disconnect', () => {
    console.log('User disconnected');
  });
});

server.listen(6001, () => {
  console.log('Socket.IO server is running on port 6001');
});
