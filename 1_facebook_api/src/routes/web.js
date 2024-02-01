import express from "express";
import * as homepageController from "../controllers/homepageController";
import * as chatBotController from "../controllers/chatBotController";

const router = express.Router();

const initWebRoutes = (app)=> {
    router.get("/", homepageController.getHomepage);
    router.get("/webhook", chatBotController.getWebhook);
    router.post("/webhook", chatBotController.postWebhook);

    return app.use("/", router);
};
export default initWebRoutes
