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
// Declare variables to hold objects.
var airplane;
var ground;

var raycaster;
var raycasterForwardCollision;
var objects = [];

var skyBoxMesh;
var texture_placeholder;



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
    renderer.setClearColor("rgb(135,206,250)");
    // Initialise stats
    stats = new Stats();
    stats.domElement.style.position = "absolute";
    stats.domElement.style.top = "0px";
    stats.domElement.style.zIndex = 100;
    docElement.appendChild(stats.domElement);
    // Camera
    camera = new THREE.PerspectiveCamera(VIEW_ANGLE, ASPECT_RATIO, NEAR_CLIPPING_PLANE, FAR_CLIPPING_PLANE);
    camera.position.set(-2157, -10, 3403);
    camera.rotation.y = Math.PI / 180;
    scene.add(camera);
    // Controls
    controls = new THREE.FlyControls(camera, document.getElementById("myDivContainer"));
    controls.movementSpeed = 100;
    controls.rollSpeed = 1;
    controls.dragToLook = true;
    // Light
    var light = new THREE.DirectionalLight(0x8c8c8c, 2.0);
    light.position.set(1, 1, 1);
    scene.add(light);
    // Raycaster
    raycaster = new THREE.Raycaster(new THREE.Vector3(), new THREE.Vector3(0, -1, 0), 0, 10);
    raycasterForwardCollision = new THREE.Raycaster(new THREE.Vector3(), new THREE.Vector3(0, 0, -1), 0, 10);
    //Bounding Box
    //firstBB = new THREE.Box3().setFromObject(airplane);
    // call initScene function.
    initScene();
    render();
}

function initScene() {
    // Add required models to the scene.
    myColladaLoader = new THREE.ColladaLoader();
    myColladaLoader.options.convertUpAxis = true;
    myColladaLoader.load("airplane.dae", function (collada) {
        // store the model in a global variable.
        airplane = collada.scene;

        // Scale your model to the correct size.
        //airplane.scale.x = 0.3;
        //airplane.scale.y = 0.3;
        //airplane.scale.z = 0.3;
        airplane.scale.x = 0.0125;
        airplane.scale.y = 0.0125;
        airplane.scale.z = 0.0125;
        //myDaeFile.updateMatrix();

        // Add the model to the scene.
        scene.add(airplane);
        camera.add(airplane);
        objects.push(airplane);
        airplane.position.set(0.1, -0.5, -2);
        //airplane.position.set(-2157, 20, 3403);
        //firstBB = new THREE.Box3().setFromObject(airplane);
    });

    myColladaLoader.load("groundv15.dae", function (collada) {
        // store the model in a global variable.
        ground = collada.scene;
        myDaeAnimations = collada.animations;
        // This will store the number of keyframes for the wind turbine animation.
        keyFrameAnimationsLength = myDaeAnimations.length;
        // Set the initial value of the last frame.
        var i;
        for (i = 0; i < keyFrameAnimationsLength; i+=1) {
            lastFrameCurrentTime[i] = 0;
        }
        var animation;
        var keyFrameAnimation;
        // This will add all the keyframe anmimations.
        for (i = 0; i < keyFrameAnimationsLength; i+=1) {
            animation = myDaeAnimations[i];
            keyFrameAnimation = new THREE.KeyFrameAnimation(animation);
            keyFrameAnimation.timescale = 1;
            keyFrameAnimation.loop = false;
            keyFrameAnimations.push(keyFrameAnimation);
        }
        // Position the model.
        ground.position.x = 5;
        ground.position.y = 5;
        ground.position.z = 0;
        // Scale your model to the correct size.
        ground.scale.x = 20;
        ground.scale.y = 20;
        ground.scale.z = 20;
        ground.updateMatrix();
        // Add the model to the scene.
        scene.add(ground);
        objects.push(ground);
        startAnimations(keyFrameAnimationsLength,keyFrameAnimations);
        //secondBB = new THREE.Box3().setFromObject(ground);
    });

    texture_placeholder = document.createElement("canvas");
    texture_placeholder.width = 100000000;
    texture_placeholder.height = 100000;

    var context = texture_placeholder.getContext("2d");
    context.fillStyle = "rgb(200, 200, 200)";
    context.fillRect(0, 0, texture_placeholder.width, texture_placeholder.height);

    var materials = [
        //loadTexture('textures/cube/deception_pass_rt.jpg'),
        //loadTexture('textures/cube/deception_pass_lf.jpg'),
        //loadTexture('textures/cube/deception_pass_up.jpg'),
        //loadTexture('textures/cube/deception_pass_dn.jpg'),
        //loadTexture('textures/cube/deception_pass_bk.jpg'),
        //loadTexture('textures/cube/deception_pass_ft.jpg')
        loadTexture("textures/clouds/bluecloud_rt.jpg"),
        loadTexture("textures/clouds/bluecloud_lf.jpg"),
        loadTexture("textures/clouds/bluecloud_up.jpg"),
        loadTexture("textures/clouds/bluecloud_dn.jpg"),
        loadTexture("textures/clouds/bluecloud_bk.jpg"),
        loadTexture("textures/clouds/bluecloud_ft.jpg")];

    skyBoxMesh = new THREE.Mesh(new THREE.BoxGeometry(8000, 8000, 8000, 7, 7, 7), new THREE.MeshFaceMaterial(materials));
    skyBoxMesh.scale.x = -1;
    //skyBoxMesh.scale.y = 5;
    //skyBoxMesh.scale.z = 5;
    scene.add(skyBoxMesh);
    //var obj1 = airplane;
    //firstBB = new THREE.Box3().setFromObject(obj1);
    //secondBB = new THREE.Box3().setFromObject(ground);
}




/* == ANIMATION FUNCTIONS == */

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

/* == END OF ANIMATION FUNCTIONS == */  





function render() {

    var delta = clock.getDelta();

    controls.update(delta);

    updateInRender(delta,keyFrameAnimationsLength,keyFrameAnimations,30);
    loopAnimations(keyFrameAnimationsLength,keyFrameAnimations,lastFrameCurrentTime);

    renderer.render(scene, camera);
    //var tmpY = camera.position.y;
    lastFrameTimeUpdate(keyFrameAnimationsLength,keyFrameAnimations,lastFrameCurrentTime);
    requestAnimationFrame(render);

    var matrix = new THREE.Matrix4();
    matrix.extractRotation(camera.matrix);
    var direction = new THREE.Vector3(0, 0, -1);
    direction = direction.applyMatrix4(matrix); 

    raycasterForwardCollision.ray.origin.copy(camera.position);
    raycasterForwardCollision.ray.direction.copy(direction);

    var intersectionsForward  = raycasterForwardCollision.intersectObjects(objects, true);

    var previousPosX = camera.position.x + 5;
    var previousPosY = camera.position.y + 5;
    var previousPosZ = camera.position.z + 5;

    if (intersectionsForward.length > 0) {
        //document.getElementById("debug").innerHTML = "COLLISION DETECTED";
        //controls.movementSpeed = 0;
        camera.position.set(previousPosX, previousPosY, previousPosZ);
        //camera.position.set(previousPos);
    }


    stats.update();
    // Display debug info.
    document.getElementById("debug").innerHTML = "x: " + camera.position.x + " y: " + camera.position.y + " z: " + camera.position.z;
    var vector = new THREE.Vector3(0, 0, 1);
    vector.applyQuaternion(controls.object.quaternion);

    /* if(airplane){
            var pos = new THREE.Vector3( camera.position.x , camera.position.y - 20 , camera.position.z );
            pos.x -= vector.x * 100;
            pos.y -= vector.y * 100;
            pos.z -= vector.z * 100;
            airplane2.position.set(pos.x, pos.y, pos.z);
            document.getElementById("debug").innerHTML = "x: " + pos.x + " y: " + pos.y + " z: " + pos.z;
    } */
}

function loadTexture(path) {
    var texture = new THREE.Texture(texture_placeholder);
    var material = new THREE.MeshBasicMaterial({map: texture, overdraw: 0.5});

    var image = new Image();

    image.onload = function() {
        texture.image = this;
        texture.needsUpdate = true;
    };

    image.src = path;

    return material;
}