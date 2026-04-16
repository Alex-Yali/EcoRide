<section class="boxAvis">
    <?php if (empty($avis)): ?>
        <p>Aucun avis à gérer.</p>
    <?php else: ?>
        <?php foreach ($avis as $a): ?>
            <section class="commentaire">
                <section class="utilisateur">
                    <img id="photo" src="/assets/images/homme.png" alt="photo de l'utilisateur">
                    <?= htmlspecialchars(ucfirst($a->getAuteurPseudo() ?? 'N/A')) ?>
                    <section class="note">
                        <section class="stars">
                            <?php for ($i = 1; $i <= 5; $i++):
                                if ($i <= $a->getNote()): ?>
                                    <span class="star filled">★</span>
                                <?php else: ?>
                                    <span class="star">☆</span>
                            <?php endif;
                            endfor; ?>
                        </section>
                    </section>
                </section>
                <section class="section-avis">
                    <section class="com">
                        <?= htmlspecialchars(ucfirst($a->getCommentaire() ?? 'N/A')) ?>
                    </section>
                    <form method="POST" action="" class="valideAvis">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf); ?>">
                        <button class="check check-green" type="submit" name="valider" value="<?= $a->getAvisId() ?>">✅ Accepter avis</button>
                        <button class="check check-red" type="submit" name="refuser" value="<?= $a->getAvisId() ?>">❌ Refuser avis</button>
                    </form>
                    <section>
                        <a title="Detail voyage" href="?avis_id=<?= $a->getAvisId() ?>#modal">
                            <img src="/assets/images/icon plus.png" id="icon-plus" alt="Ajouter">
                        </a>
                    </section>
                </section>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<!-- Modal infos voyage -->
<section id="modal" class="modal">
    <section class="infos">
        <a href="#" class="close">x</a>
        <h2>Infos Covoiturage</h2>
        <table class="infosCovoit">
            <thead>
                <tr>
                    <th>Numéro covoiturage</th>
                    <th>Pseudo passager</th>
                    <th>Email passager</th>
                    <th>Pseudo chauffeur</th>
                    <th>Email chauffeur</th>
                    <th>Lieu Départ</th>
                    <th>Date Départ</th>
                    <th>Lieu Arrivée</th>
                    <th>Date Arrivée</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($infosCovoitAvis)): ?>
                    <tr>
                        <td data-label="Numéro covoiturage"><?= htmlspecialchars(ucfirst($infosCovoitAvis->getCovoiturageId() ?? 'N/A')) ?></td>
                        <td data-label="Pseudo passager"><?= htmlspecialchars(ucfirst($infosCovoitAvis->getPassagerPseudo() ?? 'N/A')) ?></td>
                        <td data-label="Email passager"><?= htmlspecialchars(ucfirst($infosCovoitAvis->getPassagerEmail() ?? 'N/A')) ?></td>
                        <td data-label="Pseudo chauffeur"><?= htmlspecialchars(ucfirst($infosCovoitAvis->getChauffeurPseudo() ?? 'N/A')) ?></td>
                        <td data-label="Email chauffeur"><?= htmlspecialchars(ucfirst($infosCovoitAvis->getChauffeurEmail() ?? 'N/A')) ?></td>
                        <td data-label="Lieu Départ"><?= htmlspecialchars(ucfirst($infosCovoitAvis->getLieuDepart() ?? 'N/A')) ?></td>
                        <td data-label="Date Départ"><?= htmlspecialchars(ucfirst($infosCovoitAvis->getDateDepart() ?? 'N/A')) ?></td>
                        <td data-label="Lieu Arrivée"><?= htmlspecialchars(ucfirst($infosCovoitAvis->getLieuArrivee() ?? 'N/A')) ?></td>
                        <td data-label="Date Arrivée"><?= htmlspecialchars(ucfirst($infosCovoitAvis->getDateArrivee() ?? 'N/A')) ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</section>