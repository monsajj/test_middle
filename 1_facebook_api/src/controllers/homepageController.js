//todo удалить после нормального подключения базы

// const { Client } = require('pg')
// const client = new Client({
//     connectionString: process.env.DATABASE_URL,
//     ssl: {
//         rejectUnauthorized: false
//     }
// });
// client.connect();
//
// let tableName = 'public.messages'
// client.query(`SELECT * FROM ${tableName}`, (err, res) => {
//     if (err) throw err;
//     for (let row of res.rows) {
//         console.log(JSON.stringify(row));
//     }
//     client.end();
// });
// client.query('INSERT INTO ${tableName} (psid, text, media) VALUES ($1, $2, $3);', ['psid-X', 'text_message', 'image_name'], (err, res) => {
//     if (err) throw err;
//     for (let row of res.rows) {
//         console.log(JSON.stringify(row));
//     }
//     client.end();
// });

let getHomepage = (req, res) => {
    return res.render("homepage.ejs");
};

module.exports = {
    getHomepage: getHomepage
};
