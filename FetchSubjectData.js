const express = require('express');
const mysql = require('mysql2');

const app = express();
const port = 3000;

// MySQL database connection (Only create once when the server starts)
const connection = mysql.createConnection({
    host: 'localhost',       // XAMPP MySQL runs on localhost
    user: 'root',            // Default MySQL username in XAMPP
    password: '',            // Default password is empty (blank) for root
    database: 'ODBC_db'      // Your ODBC_db database name
});

// Middleware to parse JSON requests
app.use(express.json());

// API endpoint to get grades and personal data for a specific subject (based on code)
app.get('/grades/:code', (req, res) => {
    const code = req.params.code; // Get the code from the URL parameter

    // SQL query with LEFT JOIN and filter by subject code
    const query = `
        SELECT g.instructorName, g.AccountID, g.quiz1, g.quiz2, g.quiz3, g.prelim, g.midterm, g.finals, 
               p.first_name, p.middle_initial, p.surname
        FROM grade g
        LEFT JOIN personaldata p ON g.AccountID = p.AccountID
        WHERE g.code = ?`;  // Assuming the table has a column 'code' for the subject code

    // Execute query with the code as the parameter
    connection.execute(query, [code], (err, results) => {
        if (err) {
            console.error(err);
            return res.status(500).send('Error executing query');
        }

        res.status(200).json(results);
    });
});

// Start the server
app.listen(port, () => {
    console.log(`Server running on http://localhost:${port}`);
});


const forms = document.querySelectorAll(".ajax-form");

forms.forEach(form => {
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default page reload

        const formData = new FormData(this); // Collect the form data
        const actionUrl = this.action; // Get the 'action' URL of the form

        axios.post(actionUrl, formData)
            .then(response => {
                // Show a success message specific to the form
                alert(`Data from ${this.id} saved successfully!`);
            })
            .catch(error => {
                // Handle errors for this specific form
                console.error(`Error saving data for ${this.id}:`, error);
                alert(`An error occurred while saving data from ${this.id}.`);
            });
    });
});