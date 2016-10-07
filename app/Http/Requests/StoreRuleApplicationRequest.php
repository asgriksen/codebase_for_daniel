<?php

namespace App\Http\Requests;

use App\Contracts\Repositories\RuleApplicationRepository;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class StoreRuleApplicationRequest extends Request
{
    /**
     * @var RuleApplicationRepository
     */
    private $ruleApplicationRepository;

    /**
     * Create new StoreRuleApplicationRequest.
     *
     * @param RuleApplicationRepository $ruleApplicationRepository
     */
    public function __construct( RuleApplicationRepository $ruleApplicationRepository )
    {
        $this->ruleApplicationRepository = $ruleApplicationRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $layers       = $this->layersAsString();
        $ref_id_table = str_plural(strtolower($this->input('layer')));

        return [
            'rule_id' => 'exists:rules,id',
            'layer'   => 'required|in:'.$layers,
            'ref_id'  => 'required|exists:'.$ref_id_table.',id'
        ];
    }

    /**
     * Return the layers list as a comma delimited string.
     *
     * @return string
     */
    public function layersAsString()
    {
        return implode(',', $this->ruleApplicationRepository->getLayersList());
    }
}
