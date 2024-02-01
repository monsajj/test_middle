import { Client } from 'pg'
const tableName = 'public.messages'
const client = new Client({
    connectionString: process.env.DATABASE_URL,
    ssl: {
        rejectUnauthorized: false
    }
});

client.connect();

export const getLastMessageByPsid = async (psid) => {
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

export const saveNewMessage = async (data) => {
    let res = await client.query(`INSERT INTO ${tableName} (psid, text, media) VALUES ($1, $2, $3) RETURNING *;`, [data.psid, data.text, data.media]);
    if (res.rows)
        return res.rows[0]
    else
        return false;
}
