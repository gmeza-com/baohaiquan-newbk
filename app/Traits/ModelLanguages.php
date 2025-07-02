<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ModelLanguages
{
  /**
   * Get languages attributes
   *
   * @param  string $attribute attribute name
   * @param  string $locale
   * @return strinf
   */
  public function language($attribute, $locale = null)
  {
    $locale = $locale ? $locale : session('lang');

    $languages = $this->getAttribute('languages');

    $currentLanguage = $languages->where('locale', $locale)->first();

    if ($currentLanguage) {
      return $currentLanguage->{$attribute};
    }
  }

  /**
   * Saved all languages attrivutes
   *
   * @param  array $request
   * @param  array $languages
   * @return void
   */
  public function saveLanguages($request, $languages = [])
  {
    $languages = $languages ? $languages : config('cnv.languages');
    foreach ($languages as $language) {
      $item = $this->languages()->where('locale', $language['locale'])->first();

      if (isset($request['language'][$language['locale']])) {
        $data = array_merge([
          'locale' => $language['locale']
        ], $request['language'][$language['locale']]);

        if (isset($data['description'])) {
          $data['description'] = Str::limit($data['description'], 157);
        }
        if (isset($data['title'])) {
          $data['title'] =  Str::limit($data['title'], 67);
        }
        if (isset($data['slug'])) {
          $data['slug'] =  \App\Libraries\Str::friendlySlug(!$data['slug'] ? @$data['name'] : @$data['slug']);
        }

        $emptyLanguage = true;
        foreach ($data as $field => $value) {
          if ($value && !in_array($field, ['locale', 'note', 'quote', 'description']) && !is_array($value)) {
            $emptyLanguage = false;
            break;
          }
        }

        if ($emptyLanguage && !$item) {
          continue;
        }

        if ($item) {
          $item->update($data);
        } else {
          @$this->languages()->create($data);
        }
      }
    }
  }
}
