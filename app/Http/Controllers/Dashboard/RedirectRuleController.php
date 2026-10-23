<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\RedirectRuleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\RedirectRuleRequest;
use App\Http\Requests\ImportRedirectRulesRequest;
use App\Models\RedirectRule;

class RedirectRuleController extends Controller
{

    public function index(RedirectRuleDataTable $dataTable)
    {
        return $dataTable->render('dashboard.redirect-rules.index');
    }


    public function create()
    {
        return view('dashboard.redirect-rules.create');
    }


    public function store(RedirectRuleRequest $request)
    {
        $redirectRule = RedirectRule::create($request->getSanitized());
        session()->flash('message', 'Redirect Rule Created Successfully!');
        session()->flash('type', 'success');
        return redirect()->route('dashboard.redirect-rules.edit', $redirectRule);
    }


    public function show(RedirectRule $redirectRule)
    {
        //
    }


    public function edit(RedirectRule $redirectRule)
    {
        return view('dashboard.redirect-rules.edit', compact('redirectRule'));
    }


    public function update(RedirectRuleRequest $request, RedirectRule $redirectRule)
    {
        $redirectRule->update($request->getSanitized());
        session()->flash('message', 'Redirect Rule Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(RedirectRule $redirectRule)
    {
        $redirectRule->delete();
        return response()->json([
            'message' => 'Redirect Rule Deleted Successfully!'
        ]);
    }

    public function export()
    {
        $rules = [];
        \File::ensureDirectoryExists(storage_path('app/temp'));
        RedirectRule::whereEnabled(true)->chunkById(500, function ($redirectRules) use (&$rules) {
            foreach ($redirectRules as $redirectRule) {
                $rules[$redirectRule->source] = [
                    'redirect' => [
                        'to' => $redirectRule->destination,
                        "statusCode" => 301
                    ]
                ];
            }
        });
        $rules = json_encode($rules, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $template = "const RedirectRules = {rules}" . PHP_EOL . 'export default RedirectRules;' . PHP_EOL;
        $path = storage_path('app/temp/redirect-rules.js');
        \File::put($path, str($template)->replace('{rules}', $rules));
        return response()->download($path, 'redirect-rules.js')->deleteFileAfterSend();
    }

    public function import(ImportRedirectRulesRequest $request)
    {
        try {
            $file = $request->file('file');
            $fileContents = file($file->getPathname());
            array_shift($fileContents);
            $regex = '/^(\/[^\s]*)?$/i';
            $created = 0;
            $updated = 0;
            $skipped = 0;
            foreach ($fileContents as $line) {
                $data = str_getcsv($line);
                if (empty($data[0]) || empty($data[1])) {
                    $skipped++;
                    continue;
                }
                if (!preg_match($regex, $data[0]) || !preg_match($regex, $data[1])) {
                    $skipped++;
                    continue;
                }
                $source = $this->prepareRuleStr($data[0]);
                $destination = $this->prepareRuleStr($data[1]);

                $rule = RedirectRule::updateOrCreate([
                    'source' => $source,
                ], [
                    'destination' => $destination,
                    'enabled' => true,
                ]);
                if ($rule->wasRecentlyCreated) {
                    $created++;
                } else {
                    $updated++;
                }

            }
            session()->flash('type', 'success');
            session()->flash('message', "Imported $created new rules and updated $updated rules  and skipped $skipped rules successfully!");
            return back();
        } catch (\Exception $e) {
            return back()->withErrors(['file' => "Can't import the file, please make sure it's a valid CSV file"]);
        }
    }

    private function prepareRuleStr($str)
    {
        $str = str($str)->trim();
        if ($str->endsWith('/')) {
            $str = $str->replaceLast('/', '');
        }
        if (!$str->startsWith('/')) {
            $str = '/' . $str;
        }
        return $str;
    }
}
