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
        /* Custom styles for tables within prose-like content */
        .prose table {
            @apply min-w-full border-collapse border border-gray-300 rounded-lg overflow-hidden; /* Added rounded-lg and overflow-hidden for better appearance */
        }
        .prose th, .prose td {
            @apply border border-gray-300 p-3 text-sm; /* Increased padding */
        }
        .prose thead {
            @apply bg-gray-100 text-gray-700 font-semibold; /* Stronger header styling */
        }
        .prose h4 {
            @apply mt-8 mb-3 text-xl font-bold text-gray-800; /* Larger and bolder headings */
        }
        /* Specific styling for the recommendation box */
        .recommendation-box {
            @apply bg-gradient-to-r from-blue-100 to-indigo-100 p-6 rounded-xl border border-blue-200 shadow-md; /* Gradient background with shadow */
        }
        .recommendation-box h4 {
            @apply text-xl font-bold text-blue-800 mb-2 !mt-0; /* Specific heading style, override mt-8 */
        }
        .recommendation-box p {
            @apply text-blue-900 text-lg font-semibold; /* Larger text for recommendation */
        }
        .ranking-table th, .ranking-table td {
            @apply px-4 py-2; /* Adjust padding for ranking table */
        }
        .ranking-table thead {
            @apply bg-gray-200 text-gray-700;
        }
        .ranking-table tbody tr:nth-child(odd) {
            @apply bg-white;
        }
        .ranking-table tbody tr:nth-child(even) {
            @apply bg-gray-50;
        }
        .ranking-table tbody tr:hover {
            @apply bg-gray-100;
        }
        .ranking-table tbody tr.bg-green-100:hover {
            @apply bg-green-200; /* Keep hover distinct for the top rank */
        }
    </style>
</head>

{{-- Navbar --}}
@include('mahasiswa.template.navbar')
<body class="bg-blue-50 text-gray-800 pt-20">

<div class="container mx-auto p-4 md:p-8 max-w-5xl">
    <header class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">Survey Rekomendasi Magang</h1>
        <p class="text-lg text-gray-600 mt-3">Pilihlah Keahlian Yang Mahir Dilakukan dan Survey Ini Akan Menentukan Rekomendasi Magangmu!</p>
    </header>

    <main class="bg-white p-8 rounded-xl shadow-2xl"> {{-- Increased padding and shadow --}}
        <div id="input-section">
            <h2 class="text-2xl font-bold mb-6 pb-3 border-b-2 border-indigo-500 text-gray-800">1. Berikan Penilaian Keahlian</h2> {{-- Bolder and colored border --}}

            <div id="decision-matrix-container" class="mb-8"> {{-- Increased margin-bottom --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm"> {{-- Added rounded corners and subtle shadow --}}
                    <table class="min-w-full border-collapse"> {{-- Removed direct border-gray-300 here, let prose handle --}}
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 p-3 text-left font-semibold text-gray-700">Alternatif Magang</th> {{-- Aligned left and slightly bolder --}}
                        </tr>
                        </thead>
                        <tbody id="decision-matrix-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <button id="calculate-btn" class="w-full bg-indigo-600 text-white py-3.5 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-xl font-bold transition duration-200 ease-in-out transform hover:scale-105"> {{-- Changed to indigo, larger padding, bolder, transform effect --}}
            Hitung Rekomendasi Magang
        </button>

        <div id="results-section" class="mt-10 hidden"> {{-- Increased margin-top --}}
            <h2 class="text-2xl font-bold mb-6 pb-3 border-b-2 border-indigo-500 text-gray-800">
                2. Hasil Perhitungan
                <button id="toggle-details-btn" class="ml-4 px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition duration-150 ease-in-out">
                    Tampilkan Detail
                </button>
            </h2>
            <div id="calculation-details" class="hidden">
                <div id="results-output" class="prose max-w-none text-gray-700"></div> {{-- Added text color for prose content --}}
            </div>
            <div id="final-ranking-output" class="prose max-w-none mt-8"></div> {{-- Increased margin-top for final ranking --}}
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
            headerRow.innerHTML += `<th class="border border-gray-300 p-3 text-left font-semibold text-gray-700">${name}</th>`; // Consistent header styling
        });

        ALTERNATIVE_NAMES.forEach((altName, i) => {
            const row = document.createElement('tr');
            let rowHtml = `<td class="border border-gray-300 p-3 font-medium text-gray-800">${altName}</td>`; // Consistent cell styling
            CRITERIA_NAMES.forEach((critName, j) => {
                let selectHtml = `<select id="d-cell-${i}-${j}" class="w-full p-2 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 bg-white shadow-sm transition duration-150 ease-in-out">`; // Enhanced select styling
                for (const term in linguisticTerms) {
                    selectHtml += `<option value="${term}">${term}</option>`;
                }
                selectHtml += `</select>`;
                rowHtml += `<td class="border border-gray-300 p-2">${selectHtml}</td>`; // Padding for cell containing select
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
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm mb-6">
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
            </div>

            <h4>2. Solusi Ideal Positif (FPIS, A*) & Negatif (FNIS, A-)</h4>
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm mb-6">
                <table>
                    <thead><tr><th></th>${CRITERIA_NAMES.map(name => `<th>${name}</th>`).join('')}</tr></thead>
                    <tbody>
                        <tr><td><b>FPIS (A*)</b></td>${results.fpis.map(cell => `<td>${formatTFN(cell)}</td>`).join('')}</tr>
                        <tr><td><b>FNIS (A-)</b></td>${results.fnis.map(cell => `<td>${formatTFN(cell)}</td>`).join('')}</tr>
                    </tbody>
                </table>
            </div>

            <h4>3. Jarak dan Koefisien Kedekatan (CC)</h4>
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm mb-6">
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
            </div>
        `;

        // HTML for final ranking (this will be immediately visible)
        let rankingHtml = `
        <h3 class="text-xl font-bold text-gray-800 mb-4">Hasil Akhir Peringkat</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm mb-6">
            <table class="data-table ranking-table"> {{-- Added ranking-table class --}}
                <thead><tr><th>Peringkat</th><th>Alternatif Magang</th><th>Nilai CC</th></tr></thead>
                <tbody>
                    ${results.ranked.map((item, index) => `
                        <tr class="${index === 0 ? 'bg-green-100 font-bold text-green-800' : ''}"> {{-- Highlight top rank --}}
                            <td class="text-center text-lg">${index + 1}</td>
                            <td>${ALTERNATIVE_NAMES[item.alternative]}</td>
                            <td>${item.value.toFixed(5)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
        <div class="recommendation-box"> {{-- Applied recommendation-box class --}}
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h4 class="font-bold text-blue-800 mb-2">Rekomendasi Magang Terbaik Untukmu:</h4>
                    <p class="text-blue-900 text-2xl">${ALTERNATIVE_NAMES[results.ranked[0].alternative]}</p>
                </div>
                <form id="recommendation-form" action="{{ route('mahasiswa.survey.accept') }}" method="POST">
                    @csrf
                    <input type="hidden" name="recommended_job_id" value="${results.ranked[0].alternative + 1}">
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200 ease-in-out transform hover:scale-105">
                        Ajukan Sekarang!
                    </button>
                </form>
            </div>
        </div>
        `;

        resultsOutput.innerHTML = detailsHtml;
        finalRankingOutput.innerHTML = rankingHtml;
        window.scrollTo({ top: resultsSection.offsetTop - 20, behavior: 'smooth' });
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
        window.scrollTo({ top: resultsSection.offsetTop - 20, behavior: 'smooth' });
    });

    window.onload = generateInputsUI;
</script>

{{-- Footer --}}
@include('mahasiswa.template.footer')
</body>
</html>