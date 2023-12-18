require("dotenv").config();
import request from "request";
import axios from "axios";
// const { Client } = require('pg')
// const client = new Client({
//     connectionString: process.env.DATABASE_URL,
//     ssl: {
//         rejectUnauthorized: false
//     }
// });
//
// client.connect();
let postWebhook = (req, res) => {
    // Parse the request body from the POST
    let body = req.body;

    // Check the webhook event is from a Page subscription
    if (body.object === 'page') {
        // Iterate over each entry - there may be multiple if batched
        body.entry.forEach(function(entry) {
            // Gets the body of the webhook event
            let webhook_event = entry.messaging[0];
            // console.log(webhook_event);

            // Get the sender PSID
            let sender_psid = webhook_event.sender.id;
            console.log('Sender PSID: ' + sender_psid);

            // Check if the event is a message or postback and
            // pass the event to the appropriate handler function
            if (webhook_event.message) {
                handleMessage(sender_psid, webhook_event.message);
            } else if (webhook_event.postback) {
                handlePostback(sender_psid, webhook_event.postback);
            }
        });
        // Return a '200 OK' response to all events
        res.status(200).send('EVENT_RECEIVED');
    } else {
        // Return a '404 Not Found' if event is not from a page subscription
        res.sendStatus(404);
    }
};

let getWebhook = (req, res) => {
    // Your verify token. Should be a random string.
    let VERIFY_TOKEN = process.env.MY_VERIFY_FB_TOKEN;

    // Parse the query params
    let mode = req.query['hub.mode'];
    let token = req.query['hub.verify_token'];
    let challenge = req.query['hub.challenge'];

    // Checks if a token and mode is in the query string of the request
    if (mode && token) {
        // Checks the mode and token sent is correct
        if (mode === 'subscribe' && token === VERIFY_TOKEN) {
            // Responds with the challenge token from the request
            console.log('WEBHOOK_VERIFIED');
            res.status(200).send(challenge);
        } else {
            // Responds with '403 Forbidden' if verify tokens do not match
            res.sendStatus(403);
        }
    }
};

// Handles messages events
async function handleMessage(sender_psid, received_message) {
    let response;

    //Check in db if the message first one for this psid
    //todo Так как я не смог нормально подключить запросы в базу то тут проверка на то что сообщение равно "Hi"
    if (received_message.text && received_message.text === "Hi") {
        let name = await getUserName(received_message.mid, process.env.FB_PAGE_TOKEN)
        response = {
            "text": `Hello ${name}!`
        }
        // Sends the response message
        callSendAPI(sender_psid, response);
        await new Promise(resolve => setTimeout(resolve, 600));
    }

    // Check if the message contains text
    if (received_message.text) {
        // Create the payload for a basic text message
        //todo Так как я не смог нормально подключить запросы в базу то тут такое же копирование последнего сообщения, только меняется комментарий бота.
        if (received_message.text === "last phrase")
            response = {
                "text": `Last message: ${received_message.text}`
            }
        else
            response = {
                "text": `You wrote: ${received_message.text}`
            }
        // client.query('INSERT INTO public.messages (psid, text, media) VALUES ($1, $2, $3);', [sender_psid, received_message.text, 'media'], (err, res) => {
        //     if (err) throw err;
        //     for (let row of res.rows) {
        //         console.log(JSON.stringify(row));
        //     }
        //     client.end();
        // });
    } else if (received_message.attachments) {
        // Gets the URL of the message attachment
        let attachment_url = received_message.attachments[0].payload.url;
        response = {
            "attachment": {
                "type": "template",
                "payload": {
                    "template_type": "generic",
                    "elements": [{
                        "title": "You send a media",
                        "image_url": attachment_url,
                    }]
                }
            }
        }
    }

    // Sends the response message
    callSendAPI(sender_psid, response);
}

// Sends response messages via the Send API
function callSendAPI(sender_psid, response) {
    // Construct the message body
    let request_body = {
        "recipient": {
            "id": sender_psid
        },
        "message": { "text": response.text, "attachment": response.attachment }
    };

    // Send the HTTP request to the Messenger Platform
    request({
        "uri": "https://graph.facebook.com/v18.0/me/messages",
        "qs": { "access_token": process.env.FB_PAGE_TOKEN },
        "method": "POST",
        "json": request_body
    }, (err, res, body) => {
        if (!err) {
            console.log('message sent!');
        } else {
            console.error("Unable to send message:" + err);
        }
    });
}

async function getUserName(messageId, fbToken) {
    let url = `https://graph.facebook.com/${messageId}?fields=from&access_token=${fbToken}`;
    let userName = await axios.get(url)
        .then(result => {
            let name = result.data.from.name
            console.log(name);
            return name;
        })
        .catch(error => {
            console.log(error)
            return '???';
        })
    return userName;
}

module.exports = {
    postWebhook: postWebhook,
    getWebhook: getWebhook
};