<?php
function renderJobCard($title, $location, $closeDate, $startDate, $qualifications = [], $applyUrl = '#') {
    ?>
    <div class="bg-white border border-gray-200 p-4 rounded-lg shadow-sm mb-4">
        <div class="flex justify-between items-center mb-2">
            <div>
                <p class="text-lg font-semibold text-blue-800"><?= htmlspecialchars($title); ?></p>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($location); ?></p>
            </div>
            <div class="text-sm text-gray-500 text-right">
                <p><strong>Applications Close:</strong> <?= htmlspecialchars($closeDate); ?></p>
                <p><strong>Start Date:</strong> <?= htmlspecialchars($startDate); ?></p>
            </div>
        </div>

        <div class="text-sm text-gray-700 mb-3">
            <p><strong>Hiring candidates with qualifications in:</strong></p>
            <ul class="list-disc list-inside">
                <?php foreach ($qualifications as $q): ?>
                    <li><?= htmlspecialchars($q); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="flex gap-2">
            <a href="<?= htmlspecialchars($applyUrl); ?>" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Apply on employer site</a>
            <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 text-sm">Save</button>
        </div>
    </div>
    <?php
}
?>
