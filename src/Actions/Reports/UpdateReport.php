<?php

namespace Transave\ScolaBookstore\Actions\Reports;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Report;

class UpdateReport
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;
    private $uploader;
    private $report;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    public function execute()
    {
        try {
            return $this->validateRequest()
                ->setReportId()
                ->uploadFileIfExists()
                ->uploadCoverIfExists()
                ->updateReport();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function setReportId()
    {
        $this->report = Report::query()->find($this->validatedInput['report_id']);
        return $this;
    }



    private function uploadCoverIfExists(): self
    {
        if (request()->hasFile('cover_image')) {
            $file = request()->file('cover_image');

            $response = $this->uploader->uploadFile($file, 'reports', 'local');

            if ($response['success']) {
                $this->validatedInput['cover_image'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function uploadFileIfExists()
    {
        if (isset($this->request['file_path']) && $this->request['file_path']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['file_path'], 'scola-bookstore/reports', $this->report, 'file_path');
            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function updateReport()
    {
        $this->report->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->report->refresh(), 'Report updated');
    }


    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'report_id' => 'required|exists:reports,id',
            'user_id' => 'required|exists:users,id|max:255',
            'category_id' => 'sometimes|required|exists:categories,id|max:255',
            'publisher_id' => 'sometimes|required|exists:publisers,id|max:255',
            'publisher'=> 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'sometimes|required|string|max:255',
            'abstract'=> 'sometimes|required|string|max:255',   
            'publication_date' => 'sometimes|required|string|max:255',
            'publication_year' => 'sometimes|required|string|max:255',
            'report_number' => 'sometimes|required|string|max:255',
            'organization'=> 'sometimes|required|string|max:255',
            'institutional_affiliations'=> 'sometimes|required|json|max:255',
            'primary_author'=> 'sometimes|required|string|max:255',
            'contributors'=> 'sometimes|required|json|max:255',
            'keywords'=> 'sometimes|required|json|max:255',
            'summary'=> 'sometimes|required|string|max:255',
            'funding_information'=> 'sometimes|required|string|max:255',
            'file_path' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'cover_image' => 'sometimes|required|image|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'license_information' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer',
            'percentage_share' => 'sometimes|required|max:255|string',
        ]);

        $this->validatedInput = Arr::except($data, ['file_path', 'cover_image']);
        return $this;

    }
}
