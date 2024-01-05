import { Client } from 'pg'
const tableName = 'public.messages'
const client = new Client({
    connectionString: process.env.DATABASE_URL,
    ssl: {
        rejectUnauthorized: false
    }
});

client.connect();

const getLastMessageByPsid = async (psid) => {
    let res = await client.query(`SELECT * FROM ${tableName} WHERE psid = '${psid}' ORDER BY id DESC LIMIT 1`);
    let lastMessage = res.rows[0]
    if (lastMessage)
        return {
            'text': lastMessage.text,
            'media': lastMessage.media
        }
    else
        return null
}

const saveNewMessage = async (data) => {
    let res = await client.query(`INSERT INTO ${tableName} (psid, text, media) VALUES ($1, $2, $3) RETURNING *;`, [data.psid, data.text, data.media]);
    if (res.rows)
        return res.rows[0]
    else
        return false;
}

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
//     // client.end();
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

module.exports = {
    saveNewMessage: saveNewMessage,
    getLastMessageByPsid: getLastMessageByPsid
};
