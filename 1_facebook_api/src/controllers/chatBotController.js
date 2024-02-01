import 'dotenv/config';
import axios from "axios";
import {getLastMessageByPsid, saveNewMessage} from "../config/db";

export const postWebhook = (req, res) => {
    // Parse the request body from the POST
    const body = req.body;
    // Check the webhook event is from a Page subscription
    if (body.object === 'page') {
        // Iterate over each entry - there may be multiple if batched
        body.entry.forEach(function(entry) {
            // Gets the body of the webhook event
            const webhook_event = entry.messaging[0];
            // Get the sender PSID
            const sender_psid = webhook_event.sender.id;

            // Check if the event is a message or postback and pass the event to the appropriate handler function
            if (webhook_event.message) {
                try {
                    handleMessage(sender_psid, webhook_event.message);
                } catch (error) {
                    console.error(error);
                }
            }
        });
        // Return a '200 OK' response to all events
        res.status(200).send('EVENT_RECEIVED');
    } else {
        console.log('postWebhook res.sendStatus(404);')
        // Return a '404 Not Found' if event is not from a page subscription
        res.sendStatus(404);
    }
};

export const getWebhook = (req, res) => {
    // Your verify token. Should be a random string.
    const VERIFY_TOKEN = process.env.MY_VERIFY_FB_TOKEN;

    // Parse the query params
    const mode = req.query['hub.mode'];
    const token = req.query['hub.verify_token'];
    const challenge = req.query['hub.challenge'];

    // Checks if a token and mode is in the query string of the request
    if (mode && token) {
        // Checks the mode and token sent is correct
        if (mode === 'subscribe' && token === VERIFY_TOKEN) {
            // Responds with the challenge token from the request
            // console.log('WEBHOOK_VERIFIED');
            res.status(200).send(challenge);
        } else {
            // Responds with '403 Forbidden' if verify tokens do not match
            res.sendStatus(403);
        }
    }
};

// Handles messages events
const handleMessage = async (sender_psid, received_message) => {
    let response;
    const lastMessage = await getLastMessageByPsid(sender_psid)
    let newMessageData = {
        psid: sender_psid,
        text: null,
        media: null
    }
    if (received_message.text)
        newMessageData.text = received_message.text
    if (received_message.attachments && received_message.attachments.length > 0)
        newMessageData.media = received_message.attachments[0].payload.url

    //Check in db if the message first one for this psid
    if (!lastMessage) {
        const name = await getUserName(received_message.mid, process.env.FB_PAGE_TOKEN)
        response = {
            "text": `Hello ${name}!`
        }
        // Sends the response message
        await callSendAPI(sender_psid, response);
        await new Promise(resolve => setTimeout(resolve, 600));
    }

    if (received_message.text && received_message.text === "last phrase" && lastMessage) {
        if (lastMessage.text) {
            response = {
                "text": `Last message: ${lastMessage.text}`
            }
        }
        if (lastMessage.media) {
            response = {
                "attachment": {
                    "type": "template",
                    "payload": {
                        "template_type": "generic",
                        "elements": [{
                            "title": "Your last message",
                            "image_url": lastMessage.media,
                        }]
                    }
                }
            }
        }
    } else if (received_message.text) { // Check if the message contains text
        // Create the payload for a basic text message
        response = {
            "text": `You wrote: ${received_message.text}`
        }
    } else if (received_message.attachments && received_message.attachments.length > 0) {
        // Gets the URL of the message attachment
        const attachment_url = received_message.attachments[0].payload.url;
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

    // Saves the new message if it from user
    if (sender_psid !== process.env.FB_APP_PSID)
        saveNewMessage(newMessageData)
    // Sends the response message
    await callSendAPI(sender_psid, response);
}

// Sends response messages via the Send API
const callSendAPI = async (sender_psid, response) => {
    // Construct the message body
    const request_body = {
        "recipient": {
            "id": sender_psid
        },
        "message": { "text": response.text, "attachment": response.attachment }
    };
    const url = `https://graph.facebook.com/v18.0/me/messages?access_token=${process.env.FB_PAGE_TOKEN}`;
    await axios.post(url, request_body)
        .catch(error => {
            console.log(error)
            return false;
        })
    return true
}

const getUserName = async (messageId, fbToken) => {
    const url = `https://graph.facebook.com/${messageId}?fields=from&access_token=${fbToken}`;
    const response = await axios.get(url)
        .catch(error => {
            console.log(error)
            return '???';
        })
    return response.data.from.name;
}
