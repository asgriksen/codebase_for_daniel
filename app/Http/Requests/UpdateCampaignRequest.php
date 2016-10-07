<?php

namespace App\Http\Requests;

use App\Contracts\Repositories\CampaignRepository;
use App\Http\Requests\Request;
use Auth;

class UpdateCampaignRequest extends Request
{
    /**
     * @var CampaignRepository
     */
    private $campaignRepository;

    /**
     * Create new UpdateCampaignRequest instance.
     *
     * @param CampaignRepository $campaignRepository
     */
    public function __construct( CampaignRepository $campaignRepository )
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Obtain the campaign id from the uri.
        //
        $campaignId = $this->route('id');

        return Auth::check() && $this->campaignRepository->byIdAndUser($campaignId, Auth::user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
