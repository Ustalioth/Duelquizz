var conn = new WebSocket('ws://duelquizz-php:8080');
conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {
    console.log(e.data);

    conn.send('Hello World!');

};