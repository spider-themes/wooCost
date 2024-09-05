
// Image generate code
// Initialize Fabric.js Canvas
const canvas = new fabric.Canvas('canvas');

// Load the image without the original text
fabric.Image.fromURL('https://images.pexels.com/photos/5650026/pexels-photo-5650026.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', function(img) {
    img.set({
        left: 0,
        top: 0,
        selectable: false,
        width: 1200,
        height: 400,

    });
    canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));

    // Add editable text fields without overlapping the original text
    const mainText = new fabric.Text("Your Banner Heading", {
        left: 100,
        top: 150,
        fontFamily: 'Arial',
        fontSize: 30,
        fill: '#171616',
        selectable: true
    });
    canvas.add(mainText);

    const discountText = new fabric.Text("Discount up to 45% OFF", {
        left: 100,
        top: 200,
        fontFamily: 'Arial',
        fontSize: 20,
        fill: '#fff',
        selectable: true
    });
    canvas.add(discountText);

    const vendorText = new fabric.Text("Example.com", {
        left: 900,
        top: 350,
        fontFamily: 'Arial',
        fontSize: 20,
        fill: '#fff',
        selectable: true
    });
    canvas.add(vendorText);

    const buttonText = new fabric.Text("Shop Now", {
        left: 100,
        top: 250,
        fontFamily: 'Arial',
        fontSize: 25,
        fill: '#fff',
        backgroundColor: '#e74c3c',
        selectable: true
    });
    canvas.add(buttonText);
});

// Function to update text, vendor name, button text, and background color
function updateImage() {
    const valentineText = document.getElementById('valentineText').value;
    const discountTextValue = document.getElementById('discountText').value;
    const vendorName = document.getElementById('vendorName').value;
    const buttonTextValue = document.getElementById('buttonText').value;

    // Update text for main text, discount text, vendor name, and button text
    const textObjects = canvas.getObjects('text');
    textObjects[0].set('text', valentineText);
    textObjects[1].set('text', discountTextValue);
    textObjects[2].set('text', vendorName);
    textObjects[3].set('text', buttonTextValue);

    canvas.renderAll();
}

// Function to upload an additional image or logo to the canvas
function uploadImage() {
    const input = document.getElementById('imageUpload');
    const reader = new FileReader();

    reader.onload = function(event) {
        const imgObj = new Image();
        imgObj.src = event.target.result;

        imgObj.onload = function() {
            const fabricImg = new fabric.Image(imgObj);
            fabricImg.set({
                left: 200,
                top: 50,
                scaleX: 0.5, // Adjust image scale if necessary
                scaleY: 0.5,
                selectable: true
            });
            canvas.add(fabricImg);
            canvas.renderAll();
        }
    };

    if (input.files && input.files[0]) {
        reader.readAsDataURL(input.files[0]);
    }
}

// Function to upload and set a background image
function uploadBackgroundImage() {
    const input = document.getElementById('backgroundImageUpload');
    const reader = new FileReader();

    reader.onload = function(event) {
        const imgObj = new Image();
        imgObj.src = event.target.result;

        imgObj.onload = function() {
            const fabricImg = new fabric.Image(imgObj);
            fabricImg.set({
                left: 0,
                top: 0,
                scaleX: canvas.width / imgObj.width,
                scaleY: canvas.height / imgObj.height,
                selectable: false,
                objectFit: true


            });
            canvas.setBackgroundImage(fabricImg, canvas.renderAll.bind(canvas));
        }
    };

    if (input.files && input.files[0]) {
        reader.readAsDataURL(input.files[0]);
    }
}

// Function to download the edited image
function downloadImage() {
    const link = document.createElement('a');
    link.href = canvas.toDataURL({ format: 'png' });
    link.download = 'edited-image.png';
    link.click();
}