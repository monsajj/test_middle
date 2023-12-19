//todo удалить после нормального подключения базы

// const { Client } = require('pg')
// const client = new Client({
//     connectionString: process.env.DATABASE_URL,
//     ssl: {
//         rejectUnauthorized: false
//     }
// });
//
// client.connect();

// let tableName = 'public.messages'
// select all data
// client.query(`SELECT * FROM ${tableName}`, function (err, res) {
//     if (err) throw err;
//     for (let row of res.rows) {
//         console.log(JSON.stringify(row));
//     }
//     client.end();
// });

// count messages with same psid for sending "Hello <user_name>" if count is zero
// let psid = 6239245069511534
// client.query(`SELECT COUNT(*) FROM ${tableName} WHERE psid = '${psid}'`, function (err, res) {
//     if (err) throw err;
//     for (let row of res.rows) {
//         console.log(JSON.stringify(row));
//     }
//     client.end();
// });

// save new message
// client.query(`INSERT INTO ${tableName} (psid, text, media) VALUES ($1, $2, $3);`, ['psid-1', 'text_message-1', 'image_name-1'], (err, res) => {
//     if (err) throw err;
//     for (let row of res.rows) {
//         console.log(JSON.stringify(row));
//     }
//     client.end();
// });

//get last message
// let psid = 'qazwsx'
// client.query(`SELECT * FROM ${tableName} WHERE psid = '${psid}' ORDER BY id DESC LIMIT 1`, function (err, res) {
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
