document.addEventListener("DOMContentLoaded", function() {
	
	const imageFolder = "images/rescaled/";
	
    const canvas = document.getElementById('animationCanvas');
    const ctx = canvas.getContext('2d');
	// 1 pixel is 1,000,000 km (Earth-Sun distance is 148 pixels)
	// To keep units sane we measure:
	// 		mass in giga-grams (1,000,000 kg)
	//		length in giga-meters (1,000,000 km)
	// const M = 2e24; // Sun is 2*10^30 kg = 2*10^25 gg
	// const G = 6e-26; // Gravitational constant in gg-gm
	const GM = 12e-2 // Defined directly to avoid floating point awfulness.
	
	function Planet(id, name, imageFileName, mass, maxDistance, velocityAtMaxDistance, axialTilt, initialAngle, description, author){
		this.id = id;
		this.name = name;
		this.imageFileName = imageFileName;
		this.maxDistance = maxDistance;
		this.velocityAtMaxDistance = velocityAtMaxDistance;
		this.description = description;
		this.author = author;
		this.clicked = false;
		
		
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
		
		this.image = new Image();
		this.image.src = imageFileName;
		
		this.height = 20;
		this.width = 20;
		
		this.drawBody = function(star) {
			ctx.drawImage(this.image, star.x + this.x(this.theta) - this.width / 2, star.y + this.y(this.theta) - this.height / 2, this.width, this.height);
		}
		
		this.canvas = document.getElementById("animationCanvas"); // So the planet know what canvas it's on.
		// Ensuring 'this' inside event listeners refers to the Planet object
		this.canvas.addEventListener('click', this.handleClick.bind(this));
	}
	
	Planet.prototype.handleClick = function(event) {
	var rect = this.canvas.getBoundingClientRect();
	console.log(rect);
	var mouseX = event.clientX - rect.left;
	var mouseY = event.clientY - rect.top;

	// Check if the click is within the image boundaries
	if (mouseX >= Sun.x + this.x(this.theta) - this.width / 2 && mouseX <= Sun.x + this.x(this.theta) + this.width / 2 && mouseY >= Sun.y + this.y(this.theta) - this.height / 2 && mouseY <= Sun.y + this.y(this.theta) + this.height / 2) {
		this.clicked = !this.clicked; // Toggle the clicked state
		this.width = this.clicked ? 480 : 20; // Change the image width on the clicked state
		console.log(this.name);
		this.height = this.clicked ? 480 : 20; // Change the image width on the clicked state
	}
	};



	// Just one star for now!
	var Sun = {};
	
	Sun.x = 500;
	Sun.y = 500;
	
	var bodies = [];
	
	fetch("loadPlanetData.php")
		.then(response => response.json())
		.then(data => {loadData(data);
		})
		.catch(error => console.error("Error:", error));
	
	
	function loadData(data) {
		//console.log(data);
		for (i in data){
			bodies[i] = new Planet(	data[i].id,
									data[i].name,
									imageFolder + data[i].imageFileName,
									parseFloat(data[i].mass),
									parseFloat(data[i].maxDistance),
									parseFloat(data[i].velocityAtMaxDistance),
									parseFloat(data[i].axialTilt),
									parseFloat(data[i].initialAngle),
									data[i].description,
									data[i].author)
	}
	//console.log(bodies);
	}
	
	
	//bodies[0] = new Planet(1, "Earth",imageFolder + "Earth.webp", 6e18, 148, 3e-5, 0, 0);
	//bodies[1] = new Planet(2, "Mars", imageFolder + "Mars.jpg", 6e17, 250, 2.4e-5, 0, 0); 
	
	//console.log(bodies);
	
    function draw() {
         ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas
		
		// Draw the Sun
        ctx.beginPath();
        ctx.arc(Sun.x, Sun.y, 20, 0, 2 * Math.PI);
		ctx.fillStyle = "yellow";
        ctx.fill();
		
		// Draw the bodies
		for (i in bodies){
			bodies[i].drawBody(Sun);
			bodies[i].updateTheta(60*60);
		}

        requestAnimationFrame(draw); // Request the next frame
    }

    draw(); // Start the animation
});
