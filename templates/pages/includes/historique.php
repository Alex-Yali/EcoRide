<?php if ($mesCovoitsHistorique): ?>
    <?php foreach ($mesCovoitsHistorique as $c): ?>
        <section class="box-covoit">
            <p id="date-covoit"><?= htmlspecialchars($c->getDateFormatted()) ?></p>
            <section class="info-covoit">
                <section class="time-covoit">
                    <section class="start-time">
                        <p><?= htmlspecialchars($c->getLieuDepart()) ?><br><?= htmlspecialchars($c->getHeureDepartFormat()) ?></p>
                    </section>
                    <section>
                        <p class="duree"><?= htmlspecialchars($c->getDureeFormatted()) ?></p>
                        <section class="ligne"></section>
                    </section>
                    <section class="end-time">
                        <p><?= htmlspecialchars($c->getLieuArrivee()) ?><br><?= htmlspecialchars($c->getHeureArriveeFormat()) ?></p>
                    </section>
                    <section class="nbr-place">
                        <p><?= htmlspecialchars($c->getNbPlace()) ?> places</p>
                    </section>
                    <section class="prix-place">
                        <p><?= htmlspecialchars($c->getPrixPersonne()) ?> crédits</p>
                    </section>
                </section>
                <section class="perso-covoit">
                    <section class="perso">
                        <img class="icon-perso" src="<?= htmlspecialchars($c->getImageVoiture()) ?>" alt="voiture">
                        <img class="icon-perso" src="/assets/images/homme.png" alt="conducteur">
                        <section class="perso-avis">
                            <p><?= htmlspecialchars(ucfirst($c->getConducteurPseudo() ?? 'N/A')) ?><br>
                                <?= $c->getConducteurMoyenne() !== null ? round($c->getConducteurMoyenne(), 1) . ' ★' : 'Non noté' ?>
                            </p>
                        </section>
                    </section>

                    <!-- Chauffeur -->
                    <?php if ($c->getStatut() === 'Terminer'): ?>
                        <span>Trajet terminé</span>
                    <?php elseif ($c->getStatut() === 'Annuler'): ?>
                        <span>Trajet annulé</span>
                    <?php endif; ?>

                </section>
            </section>
            <!-- Verifie si l'utilisateur connecté est le conducteur du covoiturage et si à déjà donné un avis -->
            <?php if (($c->getUtilisateurId() !== $c->getConducteurId()) && !$c->getDejaAvis() && $c->getStatut() !== 'Annuler'): ?>
                <section class="avis-covoit">
                    <form action="/espace/" class="formAvis" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                        <!-- Étape 1 -->
                        <section class="formAvisCovoit">
                            <fieldset id="ajoutAvis">
                                <legend>Ce trajet s'est-il bien déroulé ?</legend>
                                <label>
                                    <input type="radio" name="avis" value="Oui" checked> Oui
                                </label>
                                <label>
                                    <input type="radio" name="avis" value="Non"> Non
                                </label>
                            </fieldset>
                            <button class="button" type="button" id="btnValider" name="action" value="valider">Valider</button>
                        </section>
                        <!-- Étape 2 -->
                        <section class="avis">
                            <input type="hidden" name="avis" class="hidden-avis">
                            <p>Votre avis nous intéresse</p>
                            <section class="separateurFiltres"></section>
                            <fieldset>
                                <legend>Commentaire</legend>
                                <label>
                                    <input type="text" name="commentaire" placeholder="Ajoutez un commentaire...">
                                </label>
                            </fieldset>
                            <section class="separateurFiltres"></section>
                            <section id="note">
                                <p>Note</p>
                                <section class="stars">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input id="r<?= $i ?>" name="rating" type="radio" value="<?= $i ?>">
                                        <label for="r<?= $i ?>" title="<?= $i ?> étoiles">★</label>
                                    <?php endfor; ?>
                                </section>
                            </section>
                            <!-- Champ caché pour identifier le covoiturage -->
                            <input type="hidden" name="covoiturage_id" value="<?= htmlspecialchars($c->getCovoiturageId()) ?>">
                            <button class="button" type="submit" id="btnEnvoyer" name="action" value="envoyer">Envoyer</button>
                        </section>
                    </form>
                </section>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
<?php else: ?>
    <p id="texteCovoit">Aucun historique de covoiturage.</p>
<?php endif; ?>