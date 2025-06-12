<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lowongan;

class SurveyController extends Controller
{
    /**
     * Display the survey page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('mahasiswa.kalkulator_fuzzy.survey');
    }

    /**
     * Process the survey results
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'alternatives' => 'required|array',
            'criteria' => 'required|array',
            'decision_matrix' => 'required|array',
        ]);

        // Here you would typically:
        // 1. Process the data with your Fuzzy TOPSIS algorithm
        // 2. Store the results if needed
        // 3. Return the results to the view

        // For now, we'll just return a success message
        return response()->json([
            'success' => true,
            'message' => 'Survey processed successfully',
            'data' => $validated
        ]);

    }

    /**
     * Accept the job recommendation from Fuzzy TOPSIS calculation
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // In SurveyController.php, update the accept method:
    // public function accept(Request $request)
    // {
    //     $request->validate([
    //         'recommended_job_id' => 'required|integer'
    //     ]);

    //     // Get the recommended job title from ALTERNATIVE_NAMES
    //     $jobTitles = [
    //         'Fullstack Developer',
    //         'Web Developer',
    //         'Machine Learning Engineer', 
    //         'Cyber Security', 
    //         'Computer Network',
    //         'Quality Assurance',
    //         'System Analyst', 
    //         'Backend Developer', 
    //         'UI/UX Designer', 
    //         'Data Analyst', 
    //         'Data Scientist'
    //     ];

    //     $recommendedIndex = $request->recommended_job_id - 1;
    //     $recommendedJobTitle = $jobTitles[$recommendedIndex] ?? 'Rekomendasi Sistem';

    //     // Store the recommendation in session
    //     session([
    //         'recommended_job' => $recommendedJobTitle,
    //         'recommended_job_id' => $request->recommended_job_id
    //     ]);

    //     return redirect()->route('mahasiswa.lowongan.index')->with([
    //         'success' => 'Rekomendasi lowongan telah diterima',
    //         'recommended_job' => $recommendedJobTitle
    //     ]);
    //     }
    public function accept(Request $request)
{
    $request->validate([
        'recommended_job_title' => 'required|string|max:255' // Validate for the job title string
    ]);

    $recommendedJobTitle = $request->input('recommended_job_title');

    // Store the recommendation in session
    // We only need the title for filtering, no specific ID from the survey's internal list
    session()->flash('recommended_job_title', $recommendedJobTitle);

    return redirect()->route('mahasiswa.lowongan.index')->with([
        'success' => 'Rekomendasi lowongan telah diterima. Menampilkan lowongan terkait ' . $recommendedJobTitle . '.',
    ]);
}

    /**
     * Cancel the current job recommendation
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        session()->forget(['recommended_lowongan_id', 'recommended_lowongan_title']);
        return redirect()->route('mahasiswa.dashboard')->with('success', 'Rekomendasi telah dibatalkan');
    }
    // In SurveyController.php, add this method:
    public function cancelRecommendation()
    {
        session()->forget(['recommended_job', 'recommended_job_id']);
        return redirect()->route('mahasiswa.lowongan.index')
            ->with('success', 'Rekomendasi telah dibatalkan');
    }
}
