document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById('animationCanvas');
    const ctx = canvas.getContext('2d');
    // const radius = 100;
    // const centerX = canvas.width / 2;
    // const centerY = canvas.height / 2;
	// const pixel_m_convert = 150 / 10^6
    let angle = 0;
	// One pixel is 1000000 km 
	var x = 650; // Initial distance is 1.5*10^8 km
	var y = 500;
	var vx = 0;
	var vy = 30/10^7; // Earth average orbital speed: 30 km/s
	var ax = 0;
	var ay = 0;
	var M = 10^7; // Sun is 2*10^30 kg
	var m = 1; // Earth is 6*10^24 kg
	const G = 0.01; // 6.6*10^-11
	var h = 0.01;
	const image = document.getElementById("rocket");
	

    function draw() {
         ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas
		
		// Draw the point
        ctx.beginPath();
        ctx.arc(500, 500, 20, 0, 2 * Math.PI);
        ctx.fill();

        // Calculate the x and y coordinates
		ax = -G*(x-500)*M*m/Math.sqrt((x-500)^2+(y-500)^2)^3*h;
		ay = -G*(y-500)*M*m/Math.sqrt((x-500)^2+(y-500)^2)^3*h;
		vx += ax*h;
		vy += ay*h;
        x += vx*h;
        y += vy*h;

        // Draw the point
        // ctx.beginPath();
        // ctx.arc(x, y, 5, 0, 2 * Math.PI);
        // ctx.fill();
		
		ctx.drawImage(image,x,y,20,20)


        // Increment the angle for the next frame
        angle += 0.05;

        requestAnimationFrame(draw); // Request the next frame
    }

    draw(); // Start the animation
});
