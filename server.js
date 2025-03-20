const express = require("express");
const webpush = require("web-push");
const bodyParser = require("body-parser");
const cors = require("cors");

const app = express();
app.use(cors());
app.use(bodyParser.json());

// Clés VAPID (Remplace par tes propres clés)
const vapidKeys = {
  publicKey: "BCqOe3D9zRDtYr8WRb1QdTaKhL1wrrVZV4hbSB-CFEGxG_SCQFOwTUdPFjv0YayqjrCxSyc-8q6-bx8g4Eeqm-o", // Remplace par ta clé publique
  privateKey: "biiMM9jDInNx1Jmj0R7AdY7j2Nq97KQGX-EsMFL3d2U", // Remplace par ta clé privée
};

// Configurer web-push
webpush.setVapidDetails(
  "mailto:diagnemaleine22@gmail.com",
  vapidKeys.publicKey,
  vapidKeys.privateKey
);

// Stockage des abonnements (Normalement, on utilise une base de données)
let subscriptions = [];

// Endpoint pour enregistrer un abonnement
app.post("/subscribe", (req, res) => {
  const subscription = req.body;
  subscriptions.push(subscription);
  res.status(201).json({ message: "Abonnement enregistré !" });
});

// Endpoint pour envoyer une notification
app.post("/send-notification", async (req, res) => {
  const payload = JSON.stringify({
    title: "Notification Push",
    body: "Ceci est un test de notification push avec Web Push API 🚀",
    icon: "https://via.placeholder.com/128", // Icône de la notification
  });

  try {
    // Envoyer la notification à tous les abonnés
    for (const sub of subscriptions) {
      await webpush.sendNotification(sub, payload);
    }
    res.status(200).json({ message: "Notification envoyée !" });
  } catch (error) {
    console.error("Erreur lors de l'envoi de la notification:", error);
    res.status(500).json({ error: "Erreur lors de l'envoi" });
  }
});

// Démarrer le serveur
const PORT = 5000;
app.listen(PORT, () => console.log(`Serveur démarré sur http://localhost:${PORT}`));
app.use(express.static(__dirname)); // Sert les fichiers statiques depuis la racine
