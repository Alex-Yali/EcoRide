db = db.getSiblingDB("ecoride");

db.createUser({
  user: "ecoride_user",
  pwd: "MotDePasseHyperSecurise123",
  roles: [{ role: "readWrite", db: "ecoride" }]
});