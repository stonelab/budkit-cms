<tpl:layout xmlns:tpl="http://budkit.org/tpl">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <form class="form-vertical col-md-12 col-sm-12 col-lg-8" method="POST" action="/admin/settings/save">
                    <fieldset class="no-margin">
                        <div class="form-group">
                            <label class="control-label" for="options[localization][timezone]">Locale timezone</label>
                            <div class="controls">
                                <tpl:select name="options[localization][timezone]" class="form-control" selected="config://content.localization.timezone">
                                    <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
                                    <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
                                    <option value="-10.0">(GMT -10:00) Hawaii</option>
                                    <option value="-9.0">(GMT -9:00) Alaska</option>
                                    <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                                    <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                                    <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                                    <option value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
                                    <option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                                    <option value="-3.5">(GMT -3:30) Newfoundland</option>
                                    <option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                                    <option value="-2.0">(GMT -2:00) Mid-Atlantic</option>
                                    <option value="-1.0">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
                                    <option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                                    <option value="1.0">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
                                    <option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                                    <option value="3.5">(GMT +3:30) Tehran</option>
                                    <option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                                    <option value="4.5">(GMT +4:30) Kabul</option>
                                    <option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                                    <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
                                    <option value="5.75">(GMT +5:45) Kathmandu</option>
                                    <option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
                                    <option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                                    <option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                                    <option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                                    <option value="9.5">(GMT +9:30) Adelaide, Darwin</option>
                                    <option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
                                    <option value="11.0">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
                                    <option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
                                </tpl:select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[localization][locale]">Locale language</label>
                            <div class="controls">
                                <tpl:select name="options[localization][locale]" class="form-control" selected="config://content.localization.locale">
                                    <option value="en_GB">English - United Kingdom (en_GB)</option>
                                    <option value="fr_FR">French - France (fr_FR)</option>
                                    <option value="de_DE">German - Germany (de_DE)</option>
                                </tpl:select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="options[localization][date-format]">Date format</label>
                            <div class="controls">
                                <tpl:select name="options[localization][date-format]" class="form-control" selected="config://content.localization.date-format">
                                    <option value="default">Time difference</option>
                                    <option value="locale">Locale time format</option>
                                </tpl:select>
                            </div>
                        </div>
                    </fieldset>
                    <input type="hidden" name="options_namespace" value="content" />
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Preferences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</tpl:layout>


