<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Fuzzy TOPSIS (Linguistik & Detail)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8; /* Softer blue-gray background */
            color: #334155; /* Default text color */
        }
        /* Custom styles for tables within prose-like content */
        .prose table {
            @apply min-w-full border-collapse border border-gray-200 rounded-xl shadow-sm overflow-hidden; /* Larger border-radius, subtle shadow */
        }
        .prose th, .prose td {
            @apply border border-gray-200 p-3.5 text-sm; /* Increased padding, lighter border */
        }
        .prose thead {
            @apply bg-blue-50 text-blue-800 font-semibold uppercase tracking-wider; /* Blue header, uppercase, tracking */
        }
        .prose tbody tr:nth-child(odd) {
            @apply bg-white;
        }
        .prose tbody tr:nth-child(even) {
            @apply bg-gray-50;
        }
        .prose tbody tr:hover {
            @apply bg-blue-50 transition-colors duration-150 ease-in-out; /* Hover effect */
        }
        .prose h4 {
            @apply mt-10 mb-4 text-xl font-extrabold text-gray-800 border-b pb-2 border-indigo-200; /* Larger, bolder, subtle border */
        }
        /* Specific styling for the recommendation box */
        .recommendation-box {
            @apply bg-gradient-to-br from-blue-600 to-indigo-700 p-8 rounded-2xl border-4 border-blue-400 shadow-xl text-white; /* Stronger gradient, border, shadow, white text */
        }
        .recommendation-box h4 {
            @apply text-2xl font-extrabold text-white mb-3 !mt-0; /* White heading, larger */
        }
        .recommendation-box p {
            @apply text-white text-3xl font-bold; /* Larger text for recommendation */
        }
        /* Styling for select dropdowns */
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='currentColor'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.5em;
            padding-right: 2.5rem;
        }
    </style>
</head>

<body>
    @include('mahasiswa.template.navbar')

    <div class="container mx-auto p-4 md:p-8 max-w-6xl mt-20"> <!-- Increased max-width for more space -->
        <header class="text-center mb-12"> <!-- Increased margin-bottom -->
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight tracking-tight">Survey Rekomendasi Magang</h1>
            <p class="text-xl text-gray-600 mt-4">Pilihlah Keahlian yang Mahir Dilakukan dan Survey Ini Akan Menentukan Rekomendasi Magangmu!</p>
        </header>

        <main class="bg-white p-8 md:p-10 rounded-3xl shadow-2xl border border-gray-100"> <!-- Enhanced padding, rounded corners, shadow, border -->
            <div id="input-section">
                <h2 class="text-3xl font-bold mb-7 pb-4 border-b-4 border-indigo-600 text-gray-800">1. Berikan Penilaian Keahlian</h2> <!-- Bolder, thicker border -->

                <div id="decision-matrix-container" class="mb-10"> <!-- Increased margin-bottom -->
                    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-md"> <!-- Added rounded corners and subtle shadow -->
                        <table class="min-w-full">
                            <thead class="bg-blue-50"> <!-- Consistent blue header -->
                                <tr>
                                    <th class="border border-gray-200 p-4 text-left font-semibold text-blue-800 uppercase tracking-wider">Alternatif Magang</th>
                                </tr>
                            </thead>
                            <tbody id="decision-matrix-body" class="divide-y divide-gray-100"> <!-- Added divider -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <button id="calculate-btn" class="w-full bg-indigo-600 text-white py-4 px-6 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-indigo-500 text-2xl font-bold transition duration-300 ease-in-out transform hover:scale-105 shadow-lg"> <!-- Larger padding, bolder, stronger hover/focus, shadow -->
                Hitung Rekomendasi Magang
            </button>

            <div id="results-section" class="mt-14 hidden"> <!-- Increased margin-top -->
                <h2 class="text-3xl font-bold mb-7 pb-4 border-b-4 border-indigo-600 text-gray-800">
                    2. Hasil Perhitungan
                    <button id="toggle-details-btn" class="ml-6 px-5 py-2.5 bg-blue-500 text-white text-base rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition duration-200 ease-in-out shadow-sm">
                        Tampilkan Detail
                    </button>
                </h2>
                <div id="calculation-details" class="hidden">
                    <div id="results-output" class="prose max-w-none text-gray-700"></div>
                </div>
                <div id="final-ranking-output" class="prose max-w-none mt-10"></div> <!-- Increased margin-top for final ranking -->
            </div>
        </main>
    </div>

    <script>
        // --- KONFIGURASI DAN DATA YANG DITENTUKAN ---
        const ALTERNATIVE_NAMES = ['Fullstack Developer', 'Backend Developer', 'UI/UX Designer', 'Data Analyst', 'Data Scientist'];
        const CRITERIA_NAMES = ['Skill', 'Minat', 'Pengalaman', 'Language/Tool Proficiency'];

        // Jenis kriteria sudah ditentukan: 'cost' untuk Harga, sisanya 'benefit'
        const CRITERIA_TYPES = ['benefit', 'benefit', 'benefit', 'benefit'];

        const linguisticTerms = {
            "Sangat Rendah": [1, 2, 3],
            "Rendah": [2, 3, 4],
            "Cukup": [4, 5, 6],
            "Tinggi": [6, 7, 8],
            "Sangat Tinggi": [7, 8, 9]
        };

        // --- Core Fuzzy TOPSIS Logic (Tidak Berubah) ---
        const ftn = {
            divide: (a, scalar) => [a[0] / scalar, a[1] / scalar, a[2] / scalar]
        };

        function fuzzyTopsis(decisionMatrix, criteriaTypes) {
            const numAlternatives = decisionMatrix.length;
            const numCriteria = decisionMatrix[0].length;

            const normalizedMatrix = [];
            for (let j = 0; j < numCriteria; j++) {
                let cMax = -Infinity;
                let aMin = Infinity;
                if (criteriaTypes[j] === 'benefit') {
                    for (let i = 0; i < numAlternatives; i++) {
                        if (decisionMatrix[i][j][2] > cMax) cMax = decisionMatrix[i][j][2];
                    }
                } else { // cost
                    for (let i = 0; i < numAlternatives; i++) {
                        if (decisionMatrix[i][j][0] < aMin) aMin = decisionMatrix[i][j][0];
                    }
                }
                for (let i = 0; i < numAlternatives; i++) {
                    if (!normalizedMatrix[i]) normalizedMatrix[i] = [];
                    if (criteriaTypes[j] === 'benefit') {
                        normalizedMatrix[i][j] = ftn.divide(decisionMatrix[i][j], cMax);
                    } else { // cost
                        normalizedMatrix[i][j] = [aMin / decisionMatrix[i][j][2], aMin / decisionMatrix[i][j][1], aMin / decisionMatrix[i][j][0]];
                    }
                }
            }

            const fpis = [];
            const fnis = [];
            for (let j = 0; j < numCriteria; j++) {
                let maxVal = [-Infinity, -Infinity, -Infinity];
                let minVal = [Infinity, Infinity, Infinity];
                for (let i = 0; i < numAlternatives; i++) {
                    if (normalizedMatrix[i][j][2] > maxVal[2]) maxVal = normalizedMatrix[i][j];
                    if (normalizedMatrix[i][j][0] < minVal[0]) minVal = normalizedMatrix[i][j];
                }
                fpis.push(maxVal);
                fnis.push(minVal);
            }

            const dPositive = [];
            const dNegative = [];
            const distance = (a, b) => Math.sqrt((1 / 3) * (Math.pow(a[0] - b[0], 2) + Math.pow(a[1] - b[1], 2) + Math.pow(a[2] - b[2], 2)));
            for (let i = 0; i < numAlternatives; i++) {
                let dPosSum = 0;
                let dNegSum = 0;
                for (let j = 0; j < numCriteria; j++) {
                    dPosSum += distance(normalizedMatrix[i][j], fpis[j]);
                    dNegSum += distance(normalizedMatrix[i][j], fnis[j]);
                }
                dPositive.push(dPosSum);
                dNegative.push(dNegSum);
            }

            const cc = [];
            for (let i = 0; i < numAlternatives; i++) {
                cc.push(dNegative[i] / (dPositive[i] + dNegative[i]));
            }

            const ranked = cc.map((value, index) => ({ alternative: index, value }))
                .sort((a, b) => b.value - a.value);

            return { normalizedMatrix, fpis, fnis, dPositive, dNegative, cc, ranked };
        }

        // --- UI Logic (Telah Diperbarui) ---
        const calculateBtn = document.getElementById('calculate-btn');
        const decisionMatrixBody = document.getElementById('decision-matrix-body');
        const resultsSection = document.getElementById('results-section');
        const resultsOutput = document.getElementById('results-output');
        const toggleDetailsBtn = document.getElementById('toggle-details-btn');
        const calculationDetails = document.getElementById('calculation-details');
        const finalRankingOutput = document.getElementById('final-ranking-output');

        function generateInputsUI() {
            const headerRow = document.querySelector('#decision-matrix-container thead tr');
            CRITERIA_NAMES.forEach(name => {
                headerRow.innerHTML += `<th class="border border-gray-200 p-4 text-left font-semibold text-blue-800 uppercase tracking-wider">${name}</th>`; // Consistent header styling
            });

            ALTERNATIVE_NAMES.forEach((altName, i) => {
                const row = document.createElement('tr');
                let rowHtml = `<td class="border border-gray-200 p-3.5 font-medium text-gray-800">${altName}</td>`; // Consistent cell styling
                CRITERIA_NAMES.forEach((critName, j) => {
                    let selectHtml = `<select id="d-cell-${i}-${j}" class="w-full p-2.5 pr-8 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 bg-white shadow-sm transition duration-150 ease-in-out cursor-pointer hover:border-indigo-400">`; // Enhanced select styling
                    for (const term in linguisticTerms) {
                        selectHtml += `<option value="${term}">${term}</option>`;
                    }
                    selectHtml += `</select>`;
                    rowHtml += `<td class="border border-gray-200 p-2.5">${selectHtml}</td>`; // Padding for cell containing select
                });
                row.innerHTML = rowHtml;
                decisionMatrixBody.appendChild(row);
            });
        }

        function gatherInputs() {
            const decisionMatrix = [];
            ALTERNATIVE_NAMES.forEach((alt, i) => {
                decisionMatrix[i] = [];
                CRITERIA_NAMES.forEach((crit, j) => {
                    const selectedTerm = document.getElementById(`d-cell-${i}-${j}`).value;
                    decisionMatrix[i][j] = linguisticTerms[selectedTerm];
                });
            });
            return { decisionMatrix, criteriaTypes: CRITERIA_TYPES };
        }

        function displayResults(results) {
            resultsSection.classList.remove('hidden');
            calculationDetails.classList.add('hidden');
            toggleDetailsBtn.textContent = 'Tampilkan Detail';

            const formatTFN = (tfn) => `(${tfn.map(v => v.toFixed(3)).join(', ')})`;

            // HTML for calculation details (Matrix, FPIS, FNIS, Distance, CC)
            let detailsHtml = `
                <h4>1. Matriks Keputusan Fuzzy Ternormalisasi</h4>
                <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-md mb-6"> <!-- Added rounded corners and subtle shadow -->
                    <table class="min-w-full">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="border border-gray-200 p-4 text-left font-semibold text-blue-800 uppercase tracking-wider">Alternatif</th>
                                ${CRITERIA_NAMES.map(name => `<th class="border border-gray-200 p-4 text-left font-semibold text-blue-800 uppercase tracking-wider">${name}</th>`).join('')}
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            ${results.normalizedMatrix.map((row, i) => `
                                <tr class="${i % 2 === 0 ? 'bg-white' : 'bg-gray-50'} hover:bg-blue-50 transition-colors duration-150 ease-in-out">
                                    <td class="border border-gray-200 p-3.5 font-medium text-gray-800"><b>${ALTERNATIVE_NAMES[i]}</b></td>
                                    ${row.map(cell => `<td class="border border-gray-200 p-3.5">${formatTFN(cell)}</td>`).join('')}
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>

                <h4>2. Solusi Ideal Positif (FPIS, A*) & Negatif (FNIS, A-)</h4>
                <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-md mb-6"> <!-- Added rounded corners and subtle shadow -->
                    <table class="min-w-full">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="border border-gray-200 p-4 text-left font-semibold text-blue-800 uppercase tracking-wider"></th>
                                ${CRITERIA_NAMES.map(name => `<th class="border border-gray-200 p-4 text-left font-semibold text-blue-800 uppercase tracking-wider">${name}</th>`).join('')}
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="bg-white hover:bg-blue-50 transition-colors duration-150 ease-in-out">
                                <td class="border border-gray-200 p-3.5 font-medium text-gray-800"><b>FPIS (A*)</b></td>${results.fpis.map(cell => `<td class="border border-gray-200 p-3.5">${formatTFN(cell)}</td>`).join('')}</tr>
                            <tr class="bg-gray-50 hover:bg-blue-50 transition-colors duration-150 ease-in-out">
                                <td class="border border-gray-200 p-3.5 font-medium text-gray-800"><b>FNIS (A-)</b></td>${results.fnis.map(cell => `<td class="border border-gray-200 p-3.5">${formatTFN(cell)}</td>`).join('')}</tr>
                        </tbody>
                    </table>
                </div>

                <h4>3. Jarak dan Koefisien Kedekatan (CC)</h4>
                <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-md mb-6"> <!-- Added rounded corners and subtle shadow -->
                    <table class="min-w-full">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="border border-gray-200 p-4 text-left font-semibold text-blue-800 uppercase tracking-wider">Alternatif</th>
                                <th class="border border-gray-200 p-4 text-left font-semibold text-blue-800 uppercase tracking-wider">Jarak ke FPIS (d+)</th>
                                <th class="border border-gray-200 p-4 text-left font-semibold text-blue-800 uppercase tracking-wider">Jarak ke FNIS (d-)</th>
                                <th class="border border-gray-200 p-4 text-left font-semibold text-blue-800 uppercase tracking-wider">Koefisien Kedekatan (CCi)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            ${ALTERNATIVE_NAMES.map((name, i) => `
                                <tr class="${i % 2 === 0 ? 'bg-white' : 'bg-gray-50'} hover:bg-blue-50 transition-colors duration-150 ease-in-out">
                                    <td class="border border-gray-200 p-3.5 font-medium text-gray-800"><b>${name}</b></td>
                                    <td class="border border-gray-200 p-3.5">${results.dPositive[i].toFixed(5)}</td>
                                    <td class="border border-gray-200 p-3.5">${results.dNegative[i].toFixed(5)}</td>
                                    <td class="border border-gray-200 p-3.5">${results.cc[i].toFixed(5)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;

            // HTML for final ranking (this will be immediately visible)
            let rankingHtml = `
            <h3 class="text-2xl font-bold text-gray-800 mb-5">Hasil Akhir Peringkat</h3>
            <div class="overflow-x-auto">
                <table class="data-table ranking-table">
                    <thead><tr><th>Peringkat</th><th>Alternatif Magang</th><th>Nilai CC</th></tr></thead>
                    <tbody>
                        ${results.ranked.map((item, index) => `
                            <tr class="${index === 0 ? 'bg-green-100 font-bold text-green-800' : ''}">
                                <td class="text-center text-lg">${index + 1}</td>
                                <td>${ALTERNATIVE_NAMES[item.alternative]}</td>
                                <td>${item.value.toFixed(5)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
            <div class="recommendation-box mt-10">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6"> <!-- Increased gap -->
                    <div class="text-center md:text-left">
                        <h4>Rekomendasi Magang Terbaik Untukmu:</h4>
                        <p class="mt-2">${ALTERNATIVE_NAMES[results.ranked[0].alternative]}</p>
                    </div>
                    <form id="recommendation-form" action="/mahasiswa/survey/accept" method="POST"> <!-- Placeholder action -->
                        <!-- @csrf (Blade directive placeholder) -->
                        <input type="hidden" name="recommended_job_id" value="${results.ranked[0].alternative + 1}">
                        <button type="submit" class="px-8 py-4 bg-white text-indigo-700 font-bold rounded-xl shadow-lg hover:bg-indigo-100 transition duration-200 ease-in-out transform hover:scale-105 text-lg">
                            Ajukan Sekarang!
                        </button>
                    </form>
                </div>
            </div>
            `;

            resultsOutput.innerHTML = detailsHtml;
            finalRankingOutput.innerHTML = rankingHtml;
            window.scrollTo({ top: resultsSection.offsetTop - 40, behavior: 'smooth' }); // Adjusted scroll offset
        }

        calculateBtn.addEventListener('click', () => {
            const inputs = gatherInputs();
            if (inputs) {
                const results = fuzzyTopsis(inputs.decisionMatrix, inputs.criteriaTypes);
                displayResults(results);
            }
        });

        toggleDetailsBtn.addEventListener('click', () => {
            if (calculationDetails.classList.contains('hidden')) {
                calculationDetails.classList.remove('hidden');
                toggleDetailsBtn.textContent = 'Sembunyikan Detail';
            } else {
                calculationDetails.classList.add('hidden');
                toggleDetailsBtn.textContent = 'Tampilkan Detail';
            }
            window.scrollTo({ top: resultsSection.offsetTop - 40, behavior: 'smooth' }); // Adjusted scroll offset
        });

        window.onload = generateInputsUI;
    </script>

    <!-- Footer Placeholder (Original was @include('mahasiswa.template.footer')) -->
    <footer class="bg-gray-800 text-white p-6 mt-20">
        <div class="container mx-auto text-center text-sm">
            &copy; 2024 Aplikasi Magang. All rights reserved.
        </div>
    </footer>
</body>
</html>
