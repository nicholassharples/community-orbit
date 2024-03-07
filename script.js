document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById('animationCanvas');
    const ctx = canvas.getContext('2d');
	// 1 pixel is 1,000,000 km (Earth-Sun distance is 148 pixels)
	// To keep units sane we measure:
	// 		mass in giga-grams (1,000,000 kg)
	//		length in giga-meters (1,000,000 km)
	// const M = 2e24; // Sun is 2*10^30 kg = 2*10^25 gg
	// const G = 6e-26; // Gravitational constant in gg-gm
	const GM = 12e-2 // Defined directly to avoid floating point awfulness.
	const image = document.getElementById("Earth");
	
	function Planet(id, name, imageUrl, mass, maxDistance, velocityAtMaxDistance, axialTilt, initialAngle){
		this.id = id;
		this.name = name;
		this.imageUrl = imageUrl;
		this.maxDistance = maxDistance;
		this.velocityAtMaxDistance = velocityAtMaxDistance;
		this.angularVelocityAtMaxDistance = velocityAtMaxDistance/maxDistance;
		this.masslessAngularMomentum = maxDistance**2 * this.angularVelocityAtMaxDistance;
		
		this.B = GM/(this.masslessAngularMomentum)**2/1e6;
		this.A = (1 - maxDistance*this.B)/maxDistance;
		this.distance = function(theta) {
			return 1/(this.A*Math.cos(theta) + this.B);
		}
		this.theta = initialAngle;
		this.updateTheta = function(deltaTime) {
			this.theta += deltaTime*this.masslessAngularMomentum/this.distance(this.theta)**2;
		}
		
		this.x = function(theta) {
			return this.distance(theta)*Math.cos(theta + axialTilt);
		}
		this.y = function(theta) {
			return this.distance(theta)*Math.sin(theta + axialTilt);
		}
	}
	
	// For testing
	
	var Sun = {};
	
	Sun.x = 500;
	Sun.y = 500;
	
	
	var Earth = new Planet(1, "Earth","None", 6e18, 148, 3e-5, 0, 0); 
	
	
	
    function draw() {
         ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas
		
		// Draw the Sun
        ctx.beginPath();
        ctx.arc(Sun.x, Sun.y, 20, 0, 2 * Math.PI);
		ctx.fillStyle = "yellow";
        ctx.fill();
		
		// Draw the Earth
		//ctx.beginPath();
		//ctx.arc(500 + Earth.x(Earth.theta), 500 + Earth.y(Earth.theta), 20, 0, 2*Math.PI);
		//ctx.fill();
		ctx.drawImage(image,500 + Earth.x(Earth.theta),500 + Earth.y(Earth.theta),20,20)
		
		// Earth.theta += 1
		Earth.updateTheta(60*60);
				
		//ctx.drawImage(image,x,y,20,20)


        requestAnimationFrame(draw); // Request the next frame
    }

    draw(); // Start the animation
});
