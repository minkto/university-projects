"use strict";
// Set the initialise function to be called when the page has loaded.
window.onload = init;

// set the size of our canvas / view onto the scene.
var WIDTH = 800;
var HEIGHT = 600;

// set camera properties / attributes.
var VIEW_ANGLE = 45;
var ASPECT_RATIO = WIDTH / HEIGHT;
var NEAR_CLIPPING_PLANE = 0.1;
var FAR_CLIPPING_PLANE = 10000;


// Declare global variables.
var renderer;
var scene;
var camera;
var stats;
var controls;
var clock = new THREE.Clock();
var myColladaLoader;


// Declare variable to hold molecule selected
var molecule;
// Puts objects in an array
var objects = [];
// Counts the amount of objects on screen.
var objectAmount = 0;


// Animation Variables
var myDaeAnimations;
var keyFrameAnimations = [];
var keyFrameAnimationsLength = 0;
var lastFrameCurrentTime = [];


function init() {
    // Scene
    scene = new THREE.Scene();
    // Set up renderer
    renderer = new THREE.WebGLRenderer({antialias: true});
    renderer.setSize(WIDTH, HEIGHT);
    var docElement = document.getElementById("myDivContainer");
    docElement.appendChild(renderer.domElement);
    renderer.setClearColor("rgb(216,216,216)");
    // Initialise stats
    stats = new Stats();
    stats.domElement.style.position = "absolute";
    stats.domElement.style.top = "0px";
    stats.domElement.style.zIndex = 100;
    docElement.appendChild(stats.domElement);
    // Camera
    camera = new THREE.PerspectiveCamera(VIEW_ANGLE, ASPECT_RATIO, NEAR_CLIPPING_PLANE, FAR_CLIPPING_PLANE);
    camera.position.set(4, 3, 239);
    camera.rotation.y = Math.PI / 180;
    scene.add(camera);
    // Controls
    controls = new THREE.FlyControls(camera, document.getElementById("myDivContainer"));
    controls.movementSpeed = 1000;
    controls.rollSpeed = 1;
    controls.dragToLook = true;
    // Light
    var light = new THREE.DirectionalLight(0x8c8c8c, 2.0);
    light.position.set(1, 1, 1);
    scene.add(light);
	initScene('solid_particles.dae','Solid');
	
    render();
}

// This function handles what description to output for whatever is on the screen.
function description(theDescription) {
	if(theDescription === "Gas") {
		document.getElementById("desc").innerHTML = "<p>Gas molecules move very fast in the air.They are not packed together like solids.<br> They move much more easily then liquids as well.</p>";
	} else if (theDescription === "Solid") {
		document.getElementById("desc").innerHTML = "<p> Solid molecules packed very tightly together. There is small vibration like movement <br>in which can become more prominent when heat energy is applied.";
	} else if (theDescription === "Liquid") {
		document.getElementById("desc").innerHTML = "Liquid molecules move around each other slightly. They are attached <br>via the positve charges on hydrogen atoms and negative charges on carbon atoms";
	} else if (theDescription === "Methane") {
		document.getElementById("desc").innerHTML = "Methane is one of the simplest form of a Hydrocarbon.";
	} else if (theDescription === "Ethane") {
		document.getElementById("desc").innerHTML = "Ethane consists of two carbon atoms and six hydrogen atoms.";
	} else  {
		document.getElementById("desc").innerHTML = "Click on any of the options above for a little description";
	} 	
}



function initScene(theMolecule,descMolecule) {

	
	if(objectAmount >= 1) {
		alert("There is already one molecule object present. Please remove one before adding another.");
	} else {
		description(descMolecule);
	
		// Add required models to the scene.
		myColladaLoader = new THREE.ColladaLoader();
		myColladaLoader.options.convertUpAxis = true;

		myColladaLoader.load(theMolecule, function (collada) {
			// store the model in a global variable.
			molecule = collada.scene;
			myDaeAnimations = collada.animations;
			// This will store the number of keyframes for the molecule animations.
			keyFrameAnimationsLength = myDaeAnimations.length;
			// Set the initial value of the last frame.
			var i;
			var animation;
			var keyFrameAnimation;
			
			for (i = 0; i < keyFrameAnimationsLength; i+=1) {
				lastFrameCurrentTime[i] = 0;
			}
			
			// This will add all the keyframe anmimations.
			for (i = 0; i < keyFrameAnimationsLength; i+=1) {
				animation = myDaeAnimations[i];
				keyFrameAnimation = new THREE.KeyFrameAnimation(animation);
				keyFrameAnimation.timescale = 1;
				keyFrameAnimation.loop = false;
				keyFrameAnimations.push(keyFrameAnimation);
			}
			// Position the model.
			molecule.position.x = 0;
			molecule.position.y = 0;
			molecule.position.z = 0;
			// Scale your model to the correct size.
			molecule.scale.x = 2;
			molecule.scale.y = 2;
			molecule.scale.z = 2;
			molecule.updateMatrix();
			// Add the model to the scene.
			scene.add(molecule);
			objects.push(molecule);
			startAnimations(keyFrameAnimationsLength,keyFrameAnimations);
			objectAmount = 1;
			
		});
	}
}


function removeObject() {	
  scene.remove(molecule);
  keyFrameAnimations = [];
  keyFrameAnimationsLength = 0;
  lastFrameCurrentTime = [];
  objectAmount = 0;
  document.getElementById("desc").innerHTML = "Click on any of the options above for a little description";
}	
	

// This function is what allows the animation to begin.
function startAnimations(theKeyAnimationsLength,theKeyFrameAnimations) {
    var i;
    var animation;
    for (i = 0; i < theKeyAnimationsLength; i+=1) {
        animation = theKeyFrameAnimations[i];
        animation.play();
    }
}
// This function handles an event where animations
function loopAnimations(theKeyAnimationsLength,theKeyFrameAnimations,theLastFrameCurrentTime) {
    var i;
    for (i = 0; i < theKeyAnimationsLength; i+=1) {
        if(theKeyFrameAnimations[i].isPlaying && !theKeyFrameAnimations[i].isPaused) {
            if(theKeyFrameAnimations[i].currentTime === theLastFrameCurrentTime[i]) {
                theKeyFrameAnimations[i].stop();
                theKeyFrameAnimations[i].play();
                theLastFrameCurrentTime[i] = 0;
            }
        }
    }
}
/* This method helps to animate the general speed of the object at each frame at a relatively fast speed*/
function updateInRender(time,theKeyFrameAnimationsLength,theKeyFrameAnimations,speed) {
    var i;
    var animation;
    for (i = 0; i < theKeyFrameAnimationsLength; i+=1) {
        animation = theKeyFrameAnimations[i];
        animation.update(time + speed);
    }
}
// This function will update the frame times.
function lastFrameTimeUpdate(theKeyAnimationsLength,theKeyFrameAnimations,theLastFrameCurrentTime) {
    var i;
    for (i = 0; i < theKeyAnimationsLength; i+=1) {
        theLastFrameCurrentTime[i] = theKeyFrameAnimations[i].currentTime;
    }
}





function render() {

    var delta = clock.getDelta();

    controls.update(delta);

    updateInRender(delta,keyFrameAnimationsLength,keyFrameAnimations,30);
    loopAnimations(keyFrameAnimationsLength,keyFrameAnimations,lastFrameCurrentTime);

    renderer.render(scene, camera);
    lastFrameTimeUpdate(keyFrameAnimationsLength,keyFrameAnimations,lastFrameCurrentTime);
    requestAnimationFrame(render);


    stats.update();
    var vector = new THREE.Vector3(0, 0, 1);
    vector.applyQuaternion(controls.object.quaternion);


} 