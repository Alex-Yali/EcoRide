<!-- Voitures -->
<?php if (empty($voituresUtilisateur)): ?>
    <p>Vous n'avez aucun véhicule associé à votre compte.</p>
<?php else: ?>
    <table class="tableauVoiture">
        <thead>
            <tr>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Immat</th>
                <th>Couleur</th>
                <th>Énergie</th>
                <th>Date 1ère immat</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($voituresUtilisateur as $voiture): ?>
                <tr>
                    <td><?= htmlspecialchars(ucfirst($voiture->getLibelle() ?? 'N/A')) ?></td>
                    <td><?= htmlspecialchars(ucfirst($voiture->getModele() ?? 'N/A')) ?> </td>
                    <td><?= htmlspecialchars(ucfirst($voiture->getImmatriculation() ?? 'N/A')) ?></td>
                    <td><?= htmlspecialchars(ucfirst($voiture->getCouleur() ?? 'N/A')) ?></td>
                    <td><?= htmlspecialchars(ucfirst($voiture->getEnergie() ?? 'N/A')) ?></td>
                    <td><?= htmlspecialchars(ucfirst($voiture->getDatePremiereImmatriculation() ?? 'N/A')) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>