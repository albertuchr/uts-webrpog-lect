<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Garamond:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #122620; /* Charcoal */
            color: #b68d40; /* Cream for text */
            font-family: 'Garamond', serif; /* Font style set to Garamond */
        }

        .container {
            margin-top: 30px;
            padding: 30px;
            border-radius: 10px; /* Rounded corners for the container */
            background-color: rgba(18, 38, 32, 0.9); /* Slightly transparent background for contrast */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Shadow for depth */
        }

        h2 {
            text-align: center; /* Center titles */
            font-size: 2.5em; /* Larger font size for main title */
            font-weight: bold; /* Bold for emphasis */
            margin-bottom: 20px; /* Space below title */
        }

        label {
            font-weight: bold; /* Bold labels for emphasis */
        }

        input, textarea {
            background-color: #f4ebd0; /* Cream for input fields */
            color: #122620; /* Charcoal for text */
            border: 1px solid #d6ad60; /* Gold border */
            border-radius: 5px; /* Rounded corners */
            padding: 10px; /* Padding for input fields */
            width: 100%; /* Full width for input fields */
            margin-bottom: 15px; /* Space below input fields */
            font-size: 1em; /* Font size for input fields */
        }

        button {
            background-color: #d6ad60; /* Gold */
            color: #122620; /* Charcoal */
            border: none;
            padding: 10px 15px; /* Padding for button */
            margin-top: 10px; /* Space above button */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Animation for button hover */
        }

        button:hover {
            background-color: #b68d40; /* Tan on hover */
            color: white; /* Change hover text to white */
        }

        .error {
            color: red; /* Error message color */
            text-align: center; /* Center error message */
            margin-bottom: 15px; /* Space below error message */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Event</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="post" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Date:</label>
            <input type="date" name="date" required>
            <label>Time:</label>
            <input type="time" name="time" required>
            <label>Location:</label>
            <input type="text" name="location" required>
            <label>Description:</label>
            <textarea name="description"></textarea>
            <label>Max Participants:</label>
            <input type="number" name="max_participants" required>
            <label>Upload Image:</label>
            <input type="file" name="image" required>
            <button type="submit">Create Event</button>
        </form>
    </div>
</body>
</html>
