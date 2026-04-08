<section class="boxAvis">
    <?php if (empty($avisCheck)): ?>
        <p>Vous n'avez géré aucun avis.</p>
    <?php else: ?>
        <?php foreach ($avisCheck as $a): ?>
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
                    <?php if ($a->getStatut() === 'valider'): ?>
                        <span class="avis-ok">Avis validé</span>
                    <?php else: ?>
                        <span class="avis-nok">Avis refusé</span>
                    <?php endif; ?>
                </section>
                <input type="hidden" name="avis_id" value="<?= $a->getAvisId() ?>">
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</section>