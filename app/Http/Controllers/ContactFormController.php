<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission; //
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContactFormController extends Controller
{
    /**
     * Display a listing of the contact submissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $submissions = ContactSubmission::latest()->paginate(10); //
        return response()->json($submissions);
    }

    /**
     * Store a newly created contact submission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255', //
                'email' => 'required|email|max:255', //
                'subject' => 'nullable|string|max:255', //
                'message' => 'required|string', //
                'is_read' => 'boolean', //
            ]);

            $submission = ContactSubmission::create($validatedData); //

            return response()->json([
                'message' => 'Contact form submitted successfully.',
                'submission' => $submission
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while submitting the contact form.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified contact submission.
     *
     * @param  \App\Models\ContactSubmission  $contactSubmission
     * @return \Illuminate\Http\Response
     */
    public function show(ContactSubmission $contactSubmission)
    {
        return response()->json($contactSubmission); //
    }

    /**
     * Update the specified contact submission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactSubmission  $contactSubmission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactSubmission $contactSubmission)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255', //
                'email' => 'sometimes|required|email|max:255', //
                'subject' => 'nullable|string|max:255', //
                'message' => 'sometimes|required|string', //
                'is_read' => 'sometimes|boolean', //
            ]);

            $contactSubmission->update($validatedData); //

            return response()->json([
                'message' => 'Contact submission updated successfully.',
                'submission' => $contactSubmission
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the contact submission.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified contact submission from storage.
     *
     * @param  \App\Models\ContactSubmission  $contactSubmission
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactSubmission $contactSubmission)
    {
        try {
            $contactSubmission->delete(); //
            return response()->json(['message' => 'Contact submission deleted successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the contact submission.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
