<?php

use EscolaLms\Courses\EscolaLmsCourseServiceProvider;
use EscolaLms\Courses\Models\Lesson;
use EscolaLms\ModelFields\Facades\ModelFields;
use Illuminate\Database\Migrations\Migration;

class AddAssistantIdModelFieldToLesson extends Migration
{
    public function up(): void
    {
        if (class_exists(EscolaLmsCourseServiceProvider::class) && class_exists(Lesson::class)) {
            ModelFields::addOrUpdateMetadataField(
                Lesson::class, 'assistant_id', 'text', '', ['nullable', 'string']
            );
        }
    }

    public function down(): void
    {
        ModelFields::removeMetaField(Lesson::class, 'assistant_id');
    }
}
