db = db.getSiblingDB("ecoride");

db.preferences.drop();

db.preferences.insertMany([
  {
    utilisateur_id: 2,
    preferences: {
      tabac: "Non fumeur",
      animal: "Animaux refusés",
      autre: "Musique Rap"
    }
  }
]);