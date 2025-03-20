const publicVapidKey = "BCqOe3D9zRDtYr8WRb1QdTaKhL1wrrVZV4hbSB-CFEGxG_SCQFOwTUdPFjv0YayqjrCxSyc-8q6-bx8g4Eeqm-o"; // Remplace par ta clé publique

// Vérifier si le navigateur supporte les notifications
if ("serviceWorker" in navigator) {
  send().catch(err => console.error(err));
}

async function send() {
  // Enregistrer le Service Worker
  const register = await navigator.serviceWorker.register("/sw.js");

  // S’abonner aux notifications push
  const subscription = await register.pushManager.subscribe({
    userVisibleOnly: true,
    applicationServerKey: urlBase64ToUint8Array(publicVapidKey),
  });

  // Envoyer l'abonnement au serveur
  await fetch("http://localhost:5000/subscribe", {
    method: "POST",
    body: JSON.stringify(subscription),
    headers: { "Content-Type": "application/json" },
  });

  console.log("Abonné aux notifications !");
}

// Convertir la clé publique en Uint8Array
function urlBase64ToUint8Array(base64String) {
  const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
  const base64 = (base64String + padding)
    .replace(/\-/g, "+")
    .replace(/_/g, "/");
  const rawData = window.atob(base64);
  return new Uint8Array([...rawData].map(char => char.charCodeAt(0)));
}
