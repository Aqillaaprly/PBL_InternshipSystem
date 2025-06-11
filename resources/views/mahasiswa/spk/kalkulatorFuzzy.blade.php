<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Fuzzy TOPSIS (Linguistik & Detail)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .prose table { @apply min-w-full border-collapse border border-gray-300; }
        .prose th, .prose td { @apply border border-gray-300 p-2 text-sm; }
        .prose thead { @apply bg-gray-100; }
        .prose h4 { @apply mt-6 mb-2 text-lg font-semibold; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="container mx-auto p-4 md:p-8 max-w-5xl">
        <header class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Kalkulator Fuzzy TOPSIS</h1>
            <p class="text-md text-gray-600 mt-2">Berikan penilaian kualitatif untuk setiap alternatif pada setiap kriteria.</p>
        </header>

        <main class="bg-white p-6 rounded-xl shadow-lg">
            <div id="input-section">
                <h2 class="text-2xl font-semibold mb-4 border-b pb-2">1. Berikan Penilaian</h2>

                <div id="decision-matrix-container" class="mb-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 p-2">Alternatif</th>
                                    </tr>
                            </thead>
                            <tbody id="decision-matrix-body">
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <button id="calculate-btn" class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-lg font-semibold transition duration-150 ease-in-out">Hitung Peringkat</button>

            <div id="results-section" class="mt-8 hidden">
                <h2 class="text-2xl font-semibold mb-4 border-b pb-2">
                    2. Hasil Perhitungan
                    <button id="toggle-details-btn" class="ml-4 px-3 py-1 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400">
                        Tampilkan Detail
                    </button>
                </h2>
                <div id="calculation-details" class="hidden">
                    <div id="results-output" class="prose max-w-none"></div>
                </div>
                <div id="final-ranking-output" class="prose max-w-none mt-4"></div>
            </div>
        </main>
    </div>

    <script>
        // --- KONFIGURASI DAN DATA YANG DITENTUKAN ---
        const ALTERNATIVE_NAMES = ['Fullstack Developer', 'Web Developer', 'UIUX Designer', 'Data Analyst', 'Data Scientist'];
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
        const toggleDetailsBtn = document.getElementById('toggle-details-btn'); // New element
        const calculationDetails = document.getElementById('calculation-details'); // New element
        const finalRankingOutput = document.getElementById('final-ranking-output'); // New element

        function generateInputsUI() {
            const headerRow = document.querySelector('#decision-matrix-container thead tr');
            CRITERIA_NAMES.forEach(name => {
                headerRow.innerHTML += `<th class="border border-gray-300 p-2 text-sm">${name}</th>`;
            });

            ALTERNATIVE_NAMES.forEach((altName, i) => {
                const row = document.createElement('tr');
                let rowHtml = `<td class="border border-gray-300 p-2 font-medium">${altName}</td>`;
                CRITERIA_NAMES.forEach((critName, j) => {
                    let selectHtml = `<select id="d-cell-${i}-${j}" class="w-full p-2 border-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500">`;
                    for (const term in linguisticTerms) {
                        selectHtml += `<option value="${term}">${term}</option>`;
                    }
                    selectHtml += `</select>`;
                    rowHtml += `<td class="border border-gray-300 p-1">${selectHtml}</td>`;
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
            resultsSection.classList.remove('hidden'); // Tampilkan section hasil
            calculationDetails.classList.add('hidden'); // Sembunyikan detail perhitungan default
            toggleDetailsBtn.textContent = 'Tampilkan Detail'; // Reset teks tombol

            const formatTFN = (tfn) => `(${tfn.map(v => v.toFixed(3)).join(', ')})`;

            // HTML untuk detail perhitungan (Matriks, FPIS, FNIS, Jarak, CC)
            let detailsHtml = `
                <h4>1. Matriks Keputusan Fuzzy Ternormalisasi</h4>
                <table>
                    <thead><tr><th>Alternatif</th>${CRITERIA_NAMES.map(name => `<th>${name}</th>`).join('')}</tr></thead>
                    <tbody>
                        ${results.normalizedMatrix.map((row, i) => `
                            <tr>
                                <td><b>${ALTERNATIVE_NAMES[i]}</b></td>
                                ${row.map(cell => `<td>${formatTFN(cell)}</td>`).join('')}
                            </tr>
                        `).join('')}
                    </tbody>
                </table>

                <h4>2. Solusi Ideal Positif (FPIS, A*) & Negatif (FNIS, A-)</h4>
                 <table>
                    <thead><tr><th></th>${CRITERIA_NAMES.map(name => `<th>${name}</th>`).join('')}</tr></thead>
                    <tbody>
                        <tr><td><b>FPIS (A*)</b></td>${results.fpis.map(cell => `<td>${formatTFN(cell)}</td>`).join('')}</tr>
                        <tr><td><b>FNIS (A-)</b></td>${results.fnis.map(cell => `<td>${formatTFN(cell)}</td>`).join('')}</tr>
                    </tbody>
                </table>

                <h4>3. Jarak dan Koefisien Kedekatan (CC)</h4>
                <table>
                    <thead><tr><th>Alternatif</th><th>Jarak ke FPIS (d+)</th><th>Jarak ke FNIS (d-)</th><th>Koefisien Kedekatan (CCi)</th></tr></thead>
                    <tbody>
                        ${ALTERNATIVE_NAMES.map((name, i) => `
                            <tr>
                                <td><b>${name}</b></td>
                                <td>${results.dPositive[i].toFixed(5)}</td>
                                <td>${results.dNegative[i].toFixed(5)}</td>
                                <td>${results.cc[i].toFixed(5)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;

            // HTML untuk peringkat akhir (ini yang akan terlihat langsung)
            let rankingHtml = `
                <h3 class="mt-8">Hasil Akhir Peringkat</h3>
                <table>
                    <thead class="bg-gray-200"><tr><th>Peringkat</th><th>Alternatif</th><th>Nilai CC</th></tr></thead>
                    <tbody>
                        ${results.ranked.map((item, index) => `
                            <tr class="font-medium ${index === 0 ? 'bg-green-100' : ''}">
                                <td class="text-center text-lg">${index + 1}</td>
                                <td>${ALTERNATIVE_NAMES[item.alternative]}</td>
                                <td>${item.value.toFixed(5)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;

            resultsOutput.innerHTML = detailsHtml; // Isi detail perhitungan
            finalRankingOutput.innerHTML = rankingHtml; // Isi peringkat akhir
            window.scrollTo({ top: resultsSection.offsetTop - 20, behavior: 'smooth' });
        }

        calculateBtn.addEventListener('click', () => {
            const inputs = gatherInputs();
            if (inputs) {
                const results = fuzzyTopsis(inputs.decisionMatrix, inputs.criteriaTypes);
                displayResults(results);
            }
        });

        // Event listener untuk tombol "Tampilkan Detail"
        toggleDetailsBtn.addEventListener('click', () => {
            if (calculationDetails.classList.contains('hidden')) {
                calculationDetails.classList.remove('hidden');
                toggleDetailsBtn.textContent = 'Sembunyikan Detail';
            } else {
                calculationDetails.classList.add('hidden');
                toggleDetailsBtn.textContent = 'Tampilkan Detail';
            }
            window.scrollTo({ top: resultsSection.offsetTop - 20, behavior: 'smooth' }); // Opsional: scroll kembali
        });


        window.onload = generateInputsUI;
    </script>
</body>
</html>