<script type="text/x-template" id="tmpl-wpuf-builder-stage">
<div id="form-preview-stage">
    <h4 v-if="!form_fields.length" class="text-center">
        <?php _e( 'Add fields by dragging the fields from the right sidebar to this area.', 'wpuf' ) ?>
    </h4>

    <!-- {{ field_settings.label_position }} -->

    <ul :class="['wpuf-form', 'sortable-list', 'form-label-' + label_type]">
        <li
            v-for="(field, index) in form_fields"
            :key="field.id"
            :class="[
                'field-items', 'wpuf-el', field.name, field.css, 'form-field-' + field.template,
                ('hidden' === field.input_type) ? 'hidden-field' : '',
                parseInt(editing_form_id) === parseInt(field.id) ? 'current-editing' : ''
            ]"
            :data-index="index"
            data-source="stage"
        >
            <div v-if="!is_full_width(field.template)" class="wpuf-label">
                <label :for="'wpuf-' + field.name ? field.name : 'cls'">
                    {{ field.label }} <span v-if="field.required && 'yes' === field.required" class="required">*</span>
                </label>
            </div>

            <component v-if="is_template_available(field)" :is="'form-' + field.template" :field="field"></component>

            <div v-if="is_pro_feature(field.template)" class="stage-pro-alert">
                <label class="wpuf-pro-text-alert">
                    <a :href="pro_link" target="_blank"><strong>{{ get_field_name(field.template) }}</strong> <?php _e( 'is available in Pro Version', 'wpuf' ); ?></a>
                </label>
            </div>

            <div v-if="!is_failed_to_validate(field.template)" class="control-buttons">
                <p>
                    <i class="fa fa-arrows move"></i>
                    <i class="fa fa-pencil" @click="open_field_settings(field.id)"></i>
                    <i class="fa fa-clone" @click="clone_field(field.id, index)"></i>
                    <i class="fa fa-trash-o" @click="delete_field(index)"></i>
                </p>
            </div>
        </li>

        <li v-if="!form_fields.length" class="field-items empty-list-item"></li>

        <li class="wpuf-submit">
            <div class="wpuf-label">&nbsp;</div>

            <?php do_action( 'wpuf-form-builder-template-builder-stage-submit-area' ); ?>
        </li>
    </ul><!-- .wpuf-form -->

    <div v-if="hidden_fields.length" class="hidden-field-list">
        <h4><?php _e( 'Hidden Fields', 'wpuf' ); ?></h4>

        <ul class="wpuf-form">
            <li
                v-for="(field, index) in hidden_fields"
                :class="['field-items', parseInt(editing_form_id) === parseInt(field.id) ? 'current-editing' : '']"
            >
                <strong><?php _e('key', 'wpuf'); ?></strong>: {{ field.name }} | <strong><?php _e( 'value', 'wpuf' ); ?></strong>: {{ field.meta_value }}

                <div class="control-buttons">
                    <p>
                        <i class="fa fa-pencil" @click="open_field_settings(field.id)"></i>
                        <i class="fa fa-clone" @click="clone_field(field.id, index)"></i>
                        <i class="fa fa-trash-o" @click="delete_hidden_field(field.id)"></i>
                    </p>
                </div>
            </li>
        </ul>
    </div>

    <?php do_action( 'wpuf-form-builder-template-builder-stage-bottom-area' ); ?>
</div><!-- #form-preview-stage -->
</script>

<script type="text/x-template" id="tmpl-wpuf-field-checkbox">
<div v-if="met_dependencies" class="panel-field-opt panel-field-opt-checkbox">
    <label v-if="option_field.title" :class="option_field.title_class">
        {{ option_field.title }} <help-text v-if="option_field.help_text" :text="option_field.help_text"></help-text>
    </label>
    <ul :class="[option_field.inline ? 'list-inline' : '']">
        <li v-for="(option, key) in option_field.options">
            <label>
                <input type="checkbox" :value="key" v-model="value"> {{ option }}
            </label>
        </li>
    </ul>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-field-html_help_text">
<div class="panel-field-opt panel-field-html-help-text" v-html="option_field.text"></div>
</script>

<script type="text/x-template" id="tmpl-wpuf-field-option-data">
<div class="panel-field-opt panel-field-opt-text">
    <label class="clearfix">
        {{ option_field.title }} <help-text v-if="option_field.help_text" :text="option_field.help_text"></help-text>
        <span class="pull-right">
            <input type="checkbox" v-model="show_value"> <?php _e( 'Show values', 'wpuf' ); ?>
        </span>
    </label>

    <ul :class="['option-field-option-chooser', show_value ? 'show-value' : '']">
        <li class="clearfix margin-0 header">
            <div class="selector">&nbsp;</div>

            <div class="sort-handler">&nbsp;</div>

            <div class="label">
                <?php _e( 'Label', 'wpuf' ); ?>
            </div>

            <div v-if="show_value" class="value">
                <?php _e( 'Value', 'wpuf' ) ?>
            </div>

            <div class="action-buttons">&nbsp;</div>
        </li>
    </ul>

    <ul :class="['option-field-option-chooser margin-0', show_value ? 'show-value' : '']">
        <li v-for="(option, index) in options" :key="option.id" :data-index="index" class="clearfix option-field-option">
            <div class="selector">
                <input
                    v-if="option_field.is_multiple"
                    type="checkbox"
                    :value="option.value"
                    v-model="selected"
                >
                <input
                    v-else
                    type="radio"
                    :value="option.value"
                    v-model="selected"
                    class="option-chooser-radio"
                >
            </div>

            <div class="sort-handler">
                <i class="fa fa-bars"></i>
            </div>

            <div class="label">
                <input type="text" v-model="option.label" @input="set_option_label(index, option.label)">
            </div>

            <div v-if="show_value" class="value">
                <input type="text" v-model="option.value">
            </div>

            <div class="action-buttons clearfix">
                <i class="fa fa-minus-circle" @click="delete_option(index)"></i>
            </div>
        </li>
        <li>
            <div class="plus-buttons clearfix" @click="add_option">
                <i class="fa fa-plus-circle"></i>
            </div>
        </li>
    </ul>

    <a v-if="!option_field.is_multiple && selected" href="#clear" @click.prevent="clear_selection"><?php _e( 'Clear Selection', 'wpuf' ); ?></a>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-field-option-pro-feature-alert">
<div class="panel-field-opt panel-field-opt-pro-feature">
    <label>{{ option_field.title }}</label><br>
    <label class="wpuf-pro-text-alert">
        <a :href="pro_link" target="_blank"><?php _e( 'Available in Pro Version', 'wpuf' ); ?></a>
    </label>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-field-options">
<div class="wpuf-form-builder-field-options">
    <div v-if="!parseInt(editing_field_id)" class="options-fileds-section text-center">
        <p>
            <span class="loader"></span>
        </p>
    </div>

    <div v-else>
        <div class="option-fields-section">
            <h3 class="section-title clearfix" @click="show_basic_settings = !show_basic_settings">
                {{ form_field_type_title }} <i :class="[show_basic_settings ? 'fa fa-angle-down' : 'fa fa-angle-right']"></i>
            </h3>

            <transition name="slide-fade">
                <div v-show="show_basic_settings" class="option-field-section-fields">
                    <component
                        v-for="option_field in basic_settings"
                        :key="option_field.name"
                        :is="'field-' + option_field.type"
                        :option_field="option_field"
                        :editing_form_field="editing_form_field"
                    ></component>
                </div>
            </transition>
        </div>


        <div v-if="advanced_settings.length" class="option-fields-section">
            <h3 class="section-title" @click="show_advanced_settings = !show_advanced_settings">
                {{ i18n.advanced_options }}  <i :class="[show_advanced_settings ? 'fa fa-angle-down' : 'fa fa-angle-right']"></i>
            </h3>

            <transition name="slide-fade">
                <div v-show="show_advanced_settings" class="option-field-section-fields">
                    <component
                        v-for="option_field in advanced_settings"
                        :key="option_field.name"
                        :is="'field-' + option_field.type"
                        :option_field="option_field"
                        :editing_form_field="editing_form_field"
                    ></component>
                </div>
            </transition>
        </div>
    </div>

</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-field-radio">
<div class="panel-field-opt panel-field-opt-radio">
    <label v-if="option_field.title">
        {{ option_field.title }} <help-text v-if="option_field.help_text" :text="option_field.help_text"></help-text>
    </label>

    <ul :class="[option_field.inline ? 'list-inline' : '']">
        <li v-for="(option, key) in option_field.options">
            <label>
                <input type="radio" :value="key" v-model="value"> {{ option }}
            </label>
        </li>
    </ul>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-field-select">
<div class="panel-field-opt panel-field-opt-select">
    <label v-if="option_field.title">
        {{ option_field.title }} <help-text v-if="option_field.help_text" :text="option_field.help_text"></help-text>
    </label>

    <select class="opt-select-element" v-model="value">
        <option value=""><?php _e( 'Select an option', 'wpuf' ); ?></option>
        <option v-for="(option, key) in option_field.options" :value="key">{{ option }}</option>
    </select>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-field-text">
<div v-if="met_dependencies" class="panel-field-opt panel-field-opt-text">
    <label>
        {{ option_field.title }} <help-text v-if="option_field.help_text" :text="option_field.help_text"></help-text>

        <input
            v-if="option_field.variation && 'number' === option_field.variation"
            type="number"
            v-model="value"
            @focusout="on_focusout"
            @keyup="on_keyup"
        >

        <input
            v-if="!option_field.variation"
            type="text"
            v-model="value"
            @focusout="on_focusout"
            @keyup="on_keyup"
        >
    </label>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-field-text-meta">
<div class="panel-field-opt panel-field-opt-text panel-field-opt-text-meta">
    <label>
        {{ option_field.title }} <help-text v-if="option_field.help_text" :text="option_field.help_text"></help-text>
        <input
            type="text"
            v-model="value"
        >
    </label>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-field-textarea">
<div class="panel-field-opt panel-field-opt-textarea">
    <label>
        {{ option_field.title }} <help-text v-if="option_field.help_text" :text="option_field.help_text"></help-text>

        <textarea :rows="option_field.rows || 5" v-model="value"></textarea>
    </label>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-checkbox_field">
<div class="wpuf-fields">
    <ul :class="['wpuf-fields-list', ('yes' === field.inline) ? 'wpuf-list-inline' : '']">
        <li v-if="has_options" v-for="(label, val) in field.options">
            <label>
                <input
                    type="checkbox"
                    :value="val"
                    :checked="is_selected(val)"
                    :class="class_names('checkbox_btns')"
                > {{ label }}
            </label>
        </li>
    </ul>

    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-custom_hidden_field">
<div class="wpuf-fields">
    <input
        type="text"
        :class="class_names('textfield')"
        :placeholder="field.placeholder"
        :value="field.default"
        :size="field.size"
    >
    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-custom_html">
<div class="wpuf-fields" v-html="field.html"></div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-dropdown_field">
<div class="wpuf-fields">
    <select
        :class="class_names('select_lbl')"
    >
        <option v-if="field.first" value="">{{ field.first }}</option>

        <option
            v-if="has_options"
            v-for="(label, val) in field.options"
            :value="label"
            :selected="is_selected(label)"
        >{{ label }}</option>
    </select>

    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-email_address">
<div class="wpuf-fields">
    <input
        type="email"
        :class="class_names('email')"
        :placeholder="field.placeholder"
        :value="field.default"
        :size="field.size"
    >
    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-featured_image">
<div class="wpuf-fields">
    <div :id="'wpuf-img_label-' + field.id + '-upload-container'">
        <div class="wpuf-attachment-upload-filelist" data-type="file" data-required="yes">
            <a class="button file-selector" href="#">
                <?php _e( 'Select Image', 'wpuf' ); ?>
            </a>
        </div>
    </div>

    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-fields">
<div class="wpuf-form-builder-form-fields">
    <template v-for="(section, index) in panel_sections">
        <div v-if="section.fields.length" class="panel-form-field-group clearfix">
            <h3 class="clearfix" @click="panel_toggle(index)">
                {{ section.title }} <i :class="[section.show ? 'fa fa-angle-down' : 'fa fa-angle-right']"></i>
            </h3>

            <transition name="slide-fade">
                <ul
                    v-show="section.show"
                    class="panel-form-field-buttons clearfix"
                    :id="'panel-form-field-buttons-' + section.id"
                >
                    <template v-for="field in section.fields">
                        <li
                            v-if="is_pro_feature(field)"
                            class="button button-faded"
                            :data-form-field="field"
                            data-source="panel"
                            @click="alert_pro_feature(field)"
                        >
                            <i v-if="field_settings[field].icon" :class="['fa fa-' + field_settings[field].icon]" aria-hidden="true"></i> {{ field_settings[field].title }}
                        </li>

                        <li
                            v-if="is_failed_to_validate(field)"
                            :class="['button', get_invalidate_btn_class(field)]"
                            :data-form-field="field"
                            data-source="panel"
                            @click="alert_invalidate_msg(field)"
                        >
                            <i v-if="field_settings[field].icon" :class="['fa fa-' + field_settings[field].icon]" aria-hidden="true"></i> {{ field_settings[field].title }}
                        </li>

                        <li
                            v-if="!is_pro_feature(field) && !is_failed_to_validate(field)"
                            class="button"
                            :data-form-field="field"
                            data-source="panel"
                            @click="add_form_field(field)"
                        >
                            <i v-if="field_settings[field].icon" :class="['fa fa-' + field_settings[field].icon]" aria-hidden="true"></i> {{ field_settings[field].title }}
                        </li>
                    </template>
                </ul>
            </transition>
        </div>
    </template>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-image_upload">
<div class="wpuf-fields">
    <div :id="'wpuf-img_label-' + field.id + '-upload-container'">
        <div class="wpuf-attachment-upload-filelist" data-type="file" data-required="yes">
            <a class="button file-selector wpuf_img_label_148" href="#">
                <?php _e( 'Select Image', 'wpuf' ); ?>
            </a>
        </div>
    </div>

    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-multiple_select">
<div class="wpuf-fields">
    <select
        :class="class_names('multi_label')"
        multiple
    >
        <option v-if="field.first" value="">{{ field.first }}</option>

        <option
            v-if="has_options"
            v-for="(label, val) in field.options"
            :value="label"
            :selected="is_selected(label)"
        >{{ label }}</option>
    </select>

    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-notification">
<div>
    <!-- <pre>{{ notifications.length }}</pre> -->
    <a href="#" class="button button-secondary add-notification" v-on:click.prevent="addNew"><span class="dashicons dashicons-plus-alt"></span> <?php _e( 'Add Notification', 'best-contact-form' ); ?></a>

    <div :class="[editing ? 'editing' : '', 'notification-wrap']">
    <!-- notification-wrap -->

        <div class="notification-table-wrap">
            <table class="wp-list-table widefat fixed striped posts wpuf-cf-notification-table">
                <thead>
                    <tr>
                        <th class="col-toggle">&nbsp;</th>
                        <th class="col-name"><?php _e( 'Name', 'best-contact-form' ); ?></th>
                        <th class="col-subject"><?php _e( 'Subject', 'best-contact-form' ); ?></th>
                        <th class="col-action">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(notification, index) in notifications">
                        <td class="col-toggle">
                            <a href="#" v-on:click.prevent="toggelNotification(index)">
                                <img v-if="notification.active" src="<?php echo WEFORMS_ASSET_URI; ?>/images/active.png" width="24" alt="status">
                                <img v-else src="<?php echo WEFORMS_ASSET_URI; ?>/images/inactive.png" width="24" alt="status">
                            </a>
                        </td>
                        <td class="col-name"><a href="#" v-on:click.prevent="editItem(index)">{{ notification.name }}</a></td>
                        <td class="col-subject">{{ notification.subject }}</td>
                        <td class="col-action">
                            <a href="#" v-on:click.prevent="duplicate(index)" title="<?php esc_attr_e( 'Duplicate', 'best-contact-form' ); ?>"><span class="dashicons dashicons-admin-page"></span></a>
                            <a href="#" v-on:click.prevent="editItem(index)" title="<?php esc_attr_e( 'Settings', 'best-contact-form' ); ?>"><span class="dashicons dashicons-admin-generic"></span></a>
                        </td>
                    </tr>
                    <tr v-if="!notifications.length">
                        <td colspan="4"><?php _e( 'No notifications found', 'best-contact-form' ); ?></td>
                    </tr>
                </tbody>
            </table>
        </div><!-- .notification-table-wrap -->

        <div class="notification-edit-area" v-if="notifications[editingIndex]">

            <div class="notification-head">
                <input type="text" name="" v-model="notifications[editingIndex].name" v-on:keyup.enter="editDone()" value="Admin Notification">
            </div>

            <div class="form-fields">
                <div class="notification-row">
                    <div class="row-one-half notification-field first">
                        <label for="notification-title"><?php _e( 'To', 'best-contact-form' ); ?></label>
                        <input type="text" v-model="notifications[editingIndex].to">
                        <wpuf-merge-tags filter="email_address" v-on:insert="insertValue" field="to"></wpuf-merge-tags>
                    </div>

                    <div class="row-one-half notification-field">
                        <label for="notification-title"><?php _e( 'Reply To', 'best-contact-form' ); ?></label>
                        <input type="email" v-model="notifications[editingIndex].replyTo">
                        <wpuf-merge-tags filter="email_address" v-on:insert="insertValue" field="replyTo"></wpuf-merge-tags>
                    </div>
                </div>

                <div class="notification-row notification-field">
                    <label for="notification-title"><?php _e( 'Subject', 'best-contact-form' ); ?></label>
                    <input type="text" v-model="notifications[editingIndex].subject">
                    <wpuf-merge-tags v-on:insert="insertValue" field="subject"></wpuf-merge-tags>
                </div>

                <div class="notification-row notification-field">
                    <label for="notification-title"><?php _e( 'Email Message', 'best-contact-form' ); ?></label>
                    <textarea name="" rows="6" v-model="notifications[editingIndex].message"></textarea>
                    <wpuf-merge-tags v-on:insert="insertValue" field="message"></wpuf-merge-tags>
                </div>

                <section class="advanced-fields">
                    <a href="#" class="field-toggle" v-on:click.prevent="toggleAdvanced()"><span class="dashicons dashicons-arrow-right"></span><?php _e( ' Advanced', 'best-contact-form' ); ?></a>

                    <div class="advanced-field-wrap">
                        <div class="notification-row">
                            <div class="row-one-half notification-field first">
                                <label for="notification-title"><?php _e( 'From Name', 'best-contact-form' ); ?></label>
                                <input type="text" v-model="notifications[editingIndex].fromName">
                                <wpuf-merge-tags v-on:insert="insertValue" field="fromName"></wpuf-merge-tags>
                            </div>

                            <div class="row-one-half notification-field">
                                <label for="notification-title"><?php _e( 'From Address', 'best-contact-form' ); ?></label>
                                <input type="email" name="" v-model="notifications[editingIndex].fromAddress">
                                <wpuf-merge-tags filter="email_address" v-on:insert="insertValue" field="fromAddress"></wpuf-merge-tags>
                            </div>
                        </div>

                        <div class="notification-row">
                            <div class="row-one-half notification-field first">
                                <label for="notification-title"><?php _e( 'CC', 'best-contact-form' ); ?></label>
                                <input type="email" name="" v-model="notifications[editingIndex].cc">
                                <wpuf-merge-tags filter="email_address" v-on:insert="insertValue" field="cc"></wpuf-merge-tags>
                            </div>

                            <div class="row-one-half notification-field">
                                <label for="notification-title"><?php _e( 'BCC', 'best-contact-form' ); ?></label>
                                <input type="email" name="" v-model="notifications[editingIndex].bcc">
                                <wpuf-merge-tags filter="email_address" v-on:insert="insertValue" field="bcc"></wpuf-merge-tags>
                            </div>
                        </div>
                    </div>
                </section><!-- .advanced-fields -->
            </div>

            <div class="submit-area">
                <a href="#" v-on:click.prevent="deleteItem(editingIndex)" title="<?php esc_attr_e( 'Delete', 'best-contact-form' ); ?>"><span class="dashicons dashicons-trash"></span></a>
                <button class="button button-secondary" v-on:click.prevent="editDone()"><?php _e( 'Done', 'best-contact-form' ); ?></button>
            </div>
        </div><!-- .notification-edit-area -->

    </div><!-- .notification-wrap -->
</div></script>

<script type="text/x-template" id="tmpl-wpuf-form-post_content">
<div class="wpuf-fields">
    <div class="wp-media-buttons" v-if="field.insert_image == 'yes'">
        <button type="button" class="button insert-media add_media" data-editor="content">
            <span class="dashicons dashicons-admin-media insert-photo-icon"></span> <?php _e( 'Insert Photo', 'wpuf' ); ?>
        </button>
    </div>
    <br v-if="field.insert_image == 'yes'" />

    <textarea
        v-if="'no' === field.rich"
        :class="class_names('textareafield')"
        :placeholder="field.placeholder"
        :rows="field.rows"
        :cols="field.cols"
    >{{ field.default }}</textarea>

    <text-editor v-if="'no' !== field.rich" :rich="field.rich"></text-editor>

    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-post_excerpt">
<div class="wpuf-fields">
    <textarea
        :class="class_names('textareafield')"
        :placeholder="field.placeholder"
        :rows="field.rows"
        :cols="field.cols"
    >{{ field.default }}</textarea>
    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-post_tags">
<div class="wpuf-fields">
    <input
        type="text"
        :class="class_names('textfield')"
        :placeholder="field.placeholder"
        :value="field.default"
        :size="field.size"
    >

    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-post_title">
<div class="wpuf-fields">
    <input
        type="text"
        :class="class_names('textfield')"
        :placeholder="field.placeholder"
        :value="field.default"
        :size="field.size"
    >
    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-radio_field">
<div class="wpuf-fields">
    <ul :class="['wpuf-fields-list', ('yes' === field.inline) ? 'wpuf-list-inline' : '']">
        <li v-if="has_options" v-for="(label, val) in field.options">
            <label>
                <input
                    type="radio"
                    :value="val"
                    :checked="is_selected(val)"
                    :class="class_names('radio_btns')"
                > {{ label }}
            </label>
        </li>
    </ul>

    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-section_break">
<div class="wpuf-section-wrap">
    <h2 class="wpuf-section-title">{{ field.label }}</h2>
    <div class="wpuf-section-details">{{ field.description }}</div>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-taxonomy">
<div class="wpuf-fields">
    <select
        v-if="'select' === field.type"
        :class="field.name"
        v-html="get_term_dropdown_options()"
    >
    </select>

    <div v-if="'ajax' === field.type" class="category-wrap">
        <div>
            <select>
                <option><?php _e( '— Select —', 'wpuf' ); ?></option>
                <option v-for="term in sorted_terms" :value="term.id">{{ term.name }}</option>
            </select>
        </div>
    </div>

    <div v-if="'multiselect' === field.type" class="category-wrap">
        <select
            :class="field.name"
            v-html="get_term_dropdown_options()"
            multiple
        >
        </select>
    </div>

    <div v-if="'checkbox' === field.type" class="category-wrap">
        <div v-html="get_term_checklist()"></div>
    </div>

    <input
        v-if="'text' === field.type"
        class="textfield"
        type="text"
        value=""
        size="40"
        autocomplete="off"
    >

    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-text_field">
<div class="wpuf-fields">
    <input
        type="text"
        :class="class_names('textfield')"
        :placeholder="field.placeholder"
        :value="field.default"
        :size="field.size"
    >
    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-textarea_field">
<div class="wpuf-fields">
    <textarea
        v-if="'no' === field.rich"
        :class="class_names('textareafield')"
        :placeholder="field.placeholder"
        :rows="field.rows"
        :cols="field.cols"
    >{{ field.default }}</textarea>

    <text-editor v-if="'no' !== field.rich" :rich="field.rich"></text-editor>

    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-form-website_url">
<div class="wpuf-fields">
    <input
        type="url"
        :class="class_names('url')"
        :placeholder="field.placeholder"
        :value="field.default"
        :size="field.size"
    >
    <span v-if="field.help" class="wpuf-help">{{ field.help }}</span>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-help-text">
<i class="fa fa-question-circle field-helper-text wpuf-tooltip" data-placement="top" :title="text"></i>
</script>

<script type="text/x-template" id="tmpl-wpuf-integration">
<div class="wpuf-integrations-wrap">

    <template v-if="hasIntegrations">
        <div :class="['wpuf-integration', isAvailable(integration.id) ? '' : 'collapsed']" v-for="integration in integrations">
            <div class="wpuf-integration-header">
                <div class="wpuf-integration-header-toggle">
                    <span :class="['wpuf-toggle-switch', 'big', isActive(integration.id) ? 'checked' : '']" v-on:click="toggleState(integration.id, $event.target)"></span>
                </div>
                <div class="wpuf-integration-header-label">
                    <img class="icon" :src="integration.icon" :alt="integration.title">
                    {{ integration.title }} <span class="label-premium" v-if="!isAvailable(integration.id)"><?php _e( 'Premium Feature', 'best-contact-form' ); ?></span>
                </div>

                <div class="wpuf-integration-header-actions">
                    <button type="button" class="toggle-area" v-on:click="showHide($event.target)">
                        <span class="screen-reader-text"><?php _e( 'Toggle panel', 'best-contact-form' ); ?></span>
                        <span class="toggle-indicator"></span>
                    </button>
                </div>
            </div>

            <div class="wpuf-integration-settings">

                <div v-if="isAvailable(integration.id)">
                    <component :is="'wpuf-integration-' + integration.id" :id="integration.id"></component>
                </div>
                <div v-else>
                    <?php _e( 'This feature is available on the premium version only.', 'best-contact-form' ); ?>
                    <a class="button" :href="pro_link" target="_blank"><?php _e( 'Upgrade to Pro', 'best-contact-form' ); ?></a>
                </div>

            </div>
        </div>
    </template>

    <div v-else>
        <?php _e( 'No integration found.', 'best-contact-form' ); ?>
    </div>
</div></script>

<script type="text/x-template" id="tmpl-wpuf-merge-tags">
<div class="wpuf-merge-tag-wrap">
    <a href="#" v-on:click.prevent="toggleFields($event)" class="merge-tag-link" title="<?php echo esc_attr( 'Click to toggle merge tags', 'wpuf' ); ?>"><span class="dashicons dashicons-editor-code"></span></a>

    <!-- <pre>{{ form_fields.length }}</pre> -->

    <div class="wpuf-merge-tags">
        <div class="merge-tag-section">
            <div class="merge-tag-head"><?php _e( 'Form Fields', 'wpuf' ); ?></div>

            <ul>
                <template v-if="form_fields.length">
                    <li v-for="field in form_fields">
                        <a href="#" v-on:click.prevent="insertField('field', field.name);">{{ field.label }}</a>
                    </li>
                </template>
                <li v-else><?php _e( 'No fields available', 'wpuf' ); ?></li>
            </ul>
        </div><!-- .merge-tag-section -->

        <?php
        if ( function_exists( 'wpuf_cf_get_merge_tags' ) ) {

            $merge_tags = wpuf_cf_get_merge_tags();

            foreach ($merge_tags as $section_key => $section) {
                ?>

                <div class="merge-tag-section">
                    <div class="merge-tag-head"><?php echo $section['title'] ?></div>

                    <ul>
                        <?php foreach ($section['tags'] as $key => $value) { ?>
                            <li>
                                <a href="#" v-on:click.prevent="insertField('<?php echo $key; ?>');"><?php echo $value; ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div><!-- .merge-tag-section -->

                <?php
            }
        }
        ?>
    </div><!-- .merge-tags -->
</div></script>

<script type="text/x-template" id="tmpl-wpuf-modal">
<div>
    <div :class="['wpuf-form-template-modal', show ? 'show' : 'hide' ]">

        <span class="screen-reader-text"><?php _e( 'Modal window. Press escape to close.',  'wpuf'  ); ?></span>
        <a href="#" class="close" v-on:click.prevent="closeModal()">× <span class="screen-reader-text"><?php _e( 'Close modal window',  'wpuf'  ); ?></span></a>

        <header class="modal-header">
            <slot name="header"></slot>
        </header>

        <div :class="['content-container', this.$slots.footer ? 'modal-footer' : 'no-footer']">
            <div class="content">
                <slot name="body"></slot>
            </div>
        </div>

        <footer v-if="this.$slots.footer">
            <slot name="footer"></slot>
        </footer>
    </div>
    <div :class="['wpuf-form-template-modal-backdrop', show ? 'show' : 'hide' ]"></div>
</div>
</script>

<script type="text/x-template" id="tmpl-wpuf-text-editor">
<div class="wpuf-text-editor">

    <div class="wp-core-ui wp-editor-wrap tmce-active">
        <link rel="stylesheet" :href="site_url + 'wp-includes/css/editor.css'" type="text/css" media="all">
        <link rel="stylesheet" :href="site_url + 'wp-includes/js/tinymce/skins/lightgray/skin.min.css'" type="text/css" media="all">

        <div class="wp-editor-container">
            <div class="mce-tinymce mce-container mce-panel" style="visibility: hidden; border-width: 1px;">
                <div class="mce-container-body mce-stack-layout">
                    <div class="mce-toolbar-grp mce-container mce-panel mce-stack-layout-item">
                        <div class="mce-container-body mce-stack-layout">
                            <div class="mce-container mce-toolbar mce-stack-layout-item">
                                <div class="mce-container-body mce-flow-layout">
                                    <div class="mce-container mce-flow-layout-item mce-btn-group">
                                        <div>
                                            <div v-if="is_full" class="mce-widget mce-btn mce-menubtn mce-fixed-width mce-listbox mce-btn-has-text"><button type="button"><span class="mce-txt">Paragraph</span> <i class="mce-caret"></i></button></div>
                                            <div class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-bold"></i></button></div>
                                            <div class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-italic"></i></button></div>
                                            <div class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-bullist"></i></button></div>
                                            <div class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-numlist"></i></button></div>
                                            <div class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-blockquote"></i></button></div>
                                            <div class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-alignleft"></i></button></div>
                                            <div class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-aligncenter"></i></button></div>
                                            <div class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-alignright"></i></button></div>
                                            <div class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-link"></i></button></div>
                                            <div class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-unlink"></i></button></div>
                                            <div v-if="is_full" class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-wp_more"></i></button></div>
                                            <div class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-fullscreen"></i></button></div>
                                            <div v-if="is_full" class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-wp_adv"></i></button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mce-container mce-toolbar mce-stack-layout-item">
                                <div class="mce-container-body mce-flow-layout">
                                    <div class="mce-container mce-flow-layout-item mce-btn-group">
                                        <div>
                                            <div v-if="is_full" class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-strikethrough"></i></button></div>
                                            <div v-if="is_full" class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-hr"></i></button></div>
                                            <div v-if="is_full" class="mce-widget mce-btn mce-colorbutton"><button type="button"><i class="mce-ico mce-i-forecolor"></i><span class="mce-preview"></span></button><button type="button" class="mce-open"> <i class="mce-caret"></i></button></div>
                                            <div v-if="is_full" class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-pastetext"></i></button></div>
                                            <div v-if="is_full" class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-removeformat"></i></button></div>
                                            <div v-if="is_full" class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-charmap"></i></button></div>
                                            <div v-if="is_full" class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-outdent"></i></button></div>
                                            <div v-if="is_full" class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-indent"></i></button></div>
                                            <div class="mce-widget mce-btn mce-disabled"><button type="button"><i class="mce-ico mce-i-undo"></i></button></div>
                                            <div class="mce-widget mce-btn mce-disabled"><button type="button"><i class="mce-ico mce-i-redo"></i></button></div>
                                            <div v-if="is_full" class="mce-widget mce-btn"><button type="button"><i class="mce-ico mce-i-wp_help"></i></button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mce-edit-area mce-container mce-panel mce-stack-layout-item" style="border-width: 1px 0px 0px;">
                        <div style="width: 100%; height: 150px; display: block;"></div><!-- iframe replacement div -->
                    </div>
                    <div class="mce-statusbar mce-container mce-panel mce-stack-layout-item" style="border-width: 1px 0px 0px;">
                        <div class="mce-container-body mce-flow-layout">
                            <div class="mce-path mce-flow-layout-item">
                                <div class="mce-path-item" data-index="0" aria-level="0">p</div>
                            </div>
                            <div class="mce-flow-layout-item mce-resizehandle"><i class="mce-ico mce-i-resize"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</script>
