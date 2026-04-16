<?php if ($mesCovoits): ?>
    <?php foreach ($mesCovoits as $c): ?>
        <form class="box-covoit" action="/espace/" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
            <input type="hidden" name="action_type" value="gestion_covoit">
            <p id="date-covoit"><?= htmlspecialchars($c->getDateFormatted()) ?></p>
            <section class="info-covoit">
                <section class="time-covoit">
                    <section class="start-time">
                        <p><?= htmlspecialchars($c->getLieuDepart()) ?><br><?= htmlspecialchars($c->getHeureDepartFormat()) ?></p>
                        </p>
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
                    <!-- Champ caché pour identifier le covoiturage -->
                    <input type="hidden" name="covoiturage_id" value="<?= $c->getCovoiturageId() ?>">

                    <?php if ($idUtilisateur === $c->getConducteurId()): ?>
                        <!-- Chauffeur : boutons selon le statut -->
                        <?php if ($c->getStatut() === 'Demarrer'): ?>
                            <button class="button btn-arrivee" type="submit" name="action" value="terminer">Arrivée à destination</button>
                        <?php elseif ($c->getStatut() === 'Terminer'): ?>
                            <span>Trajet terminé</span>
                        <?php elseif ($c->getStatut() === 'Annuler'): ?>
                            <span>Trajet annulé</span>
                        <?php else: ?>
                            <button class="button btn-demarrer" type="submit" name="action" value="demarrer">Démarrer</button>
                        <?php endif; ?>

                        <!-- Bouton Annuler visible pour le chauffeur -->
                        <button class="button" type="submit" id="btnAnnuler" name="action" value="annuler">Annuler le voyage</button>

                    <?php else: ?>
                        <!-- Passager : afficher seulement le statut -->
                        <?php if ($c->getStatut() === 'Demarrer'): ?>
                            <span>Trajet en cours</span>
                        <?php elseif ($c->getStatut() === 'Terminer'): ?>
                            <span>Trajet terminé</span>
                        <?php elseif ($c->getStatut() === 'Annuler'): ?>
                            <span>Trajet annulé</span>
                        <?php else: ?>
                            <span>Trajet à venir</span>
                        <?php endif; ?>
                        <!--  Bouton Annuler visible pour les passagers -->
                        <button class="button" type="submit" id="btnAnnuler" name="action" value="annuler">Annuler le voyage</button>
                    <?php endif; ?>

                </section>
            </section>
        </form>
    <?php endforeach; ?>
<?php else: ?>
    <p id="texteCovoit">Aucun covoiturage en cours.</p>
<?php endif; ?>