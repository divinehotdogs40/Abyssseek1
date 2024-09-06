<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Question Mark Circle</title>
<style>
    .container-info {
    position: relative;
    }

    .circle {
    width: 20px;
    height: 20px;
    background-color: white; /* Set gold color */
    border-radius: 50%;
    color: black; /* Set question mark color to black */
    text-align: center;
    font-size: 20px;
    cursor: pointer;
    line-height: 20px; /* Adjusted line-height */
    border: 1px solid black; /* Set border size and color */
    top: 50%; /* Adjust the distance between circle and message */
    left: 50%;
    }

    .message {
    display: none;
    position: absolute;
    top: 100%; /* Adjust the distance between circle and message */
    left: 5%;
    transform: translateX(-50%);
    padding: 5px; /* Reduced padding */
    background-color: lightblue; /* Set light blue background color */
    color: black; /* Set font color to black */
    border-radius: 5px;
    font-size: 12px; /* Reduced font size */
    width: 10%; /* Reduced width */
    }
</style>
</head>
<body>

<div class="container-info">
<div class="circle" id="circle">?</div>
<div class="message" id="message">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo cupiditate tenetur delectus assumenda eum voluptatem exercitationem? Reprehenderit repellendus dolore, delectus fuga cum quasi ut alias adipisci minus labore vero autem.</div>
</div>

<script>
const circle = document.getElementById('circle');
const message = document.getElementById('message');

let isMessageVisible = false;

function toggleMessageVisibility() {
    if (isMessageVisible) {
        message.style.display = 'none';
    } else {
        message.style.display = 'block';
    }
    isMessageVisible = !isMessageVisible;
}

circle.addEventListener('click', toggleMessageVisibility);

circle.addEventListener('mouseenter', () => {
    if (!isMessageVisible) {
        message.style.display = 'block';
    }
});

circle.addEventListener('mouseleave', () => {
    if (!isMessageVisible) {
        message.style.display = 'none';
    }
});
</script>

</body>
</html>
