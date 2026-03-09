<?php

namespace Database\Seeders;

use App\Models\Deck;
use App\Models\Flashcard;
use Illuminate\Database\Seeder;

class FlashcardSeeder extends Seeder
{
    public function run(): void
    {
        $decks = Deck::all();

        foreach ($decks as $deck) {
            $this->createFlashcardsForDeck($deck);
        }
    }

    private function createFlashcardsForDeck(Deck $deck): void
    {
        $existingCount = $deck->flashcards()->count();
        if ($existingCount > 0) {
            return; // Skip if already has flashcards
        }

        $flashcards = match ($deck->title) {
            'Laravel Essentials' => $this->laravelEssentials(),
            'JavaScript ES6+ Features' => $this->javascriptEs6(),
            'Japanese Hiragana Basics' => $this->japaneseHiragana(),
            'Spanish Vocabulary - Food' => $this->spanishFood(),
            'MySQL Fundamentals' => $this->mysqlFundamentals(),
            'Vue.js Components' => $this->vueComponents(),
            'Python for Data Science' => $this->pythonDataScience(),
            'French Verbs - Present Tense' => $this->frenchVerbs(),
            'AWS Cloud Practitioner' => $this->awsCloud(),
            'Docker & Kubernetes Basics' => $this->dockerKubernetes(),
            'Personal Study Notes' => $this->personalNotes(),
            'Interview Prep' => $this->interviewPrep(),
            'Legacy Content (Archived)' => $this->legacyContent(),
            default => $this->defaultFlashcards($deck),
        };

        foreach ($flashcards as $flashcard) {
            Flashcard::create([
                'deck_id' => $deck->id,
                'front_content' => $flashcard['front'],
                'back_content' => $flashcard['back'],
                'hint' => $flashcard['hint'] ?? null,
            ]);
        }
    }

    private function laravelEssentials(): array
    {
        return [
            ['front' => 'What is the Artisan command to create a new controller?', 'back' => 'php artisan make:controller ControllerName', 'hint' => 'make:controller'],
            ['front' => 'What does Eloquent ORM represent in Laravel?', 'back' => 'Eloquent is Laravel\'s Active Record ORM implementation for working with databases.', 'hint' => 'Active Record'],
            ['front' => 'Which file defines the web routes in Laravel?', 'back' => 'routes/web.php defines routes for web applications that use session state and CSRF protection.', 'hint' => 'web.php'],
            ['front' => 'What is a Middleware in Laravel?', 'back' => 'Middleware acts as a bridge between a request and a response, filtering HTTP requests entering your application.', 'hint' => 'HTTP filter'],
            ['front' => 'How do you define a one-to-many relationship in Eloquent?', 'back' => 'Use the hasMany method on the parent model and belongsTo on the child model.', 'hint' => 'hasMany'],
            ['front' => 'What is the purpose of a Service Provider?', 'back' => 'Service providers are the central place to configure and bootstrap your application\'s services.', 'hint' => 'bootstrap'],
            ['front' => 'How do you create a migration in Laravel?', 'back' => 'php artisan make:migration create_table_name', 'hint' => 'make:migration'],
            ['front' => 'What is Route Model Binding?', 'back' => 'Laravel automatically injects the model instance matching the route parameter ID.', 'hint' => 'auto injection'],
            ['front' => 'How do you define an accessor in a model?', 'back' => 'Create a get{Attribute}Attribute method or use the Accessible trait.', 'hint' => 'get method'],
            ['front' => 'What is the difference between fill() and create()?', 'back' => 'fill() assigns attributes but doesn\'t save; create() assigns and saves to the database.', 'hint' => 'save vs no save'],
        ];
    }

    private function javascriptEs6(): array
    {
        return [
            ['front' => 'What is the syntax for an arrow function?', 'back' => 'const add = (a, b) => a + b;', 'hint' => '=>'],
            ['front' => 'How does array destructuring work?', 'back' => 'const [first, second] = myArray; extracts elements into variables.', 'hint' => '[] assignment'],
            ['front' => 'What is template literal syntax?', 'back' => 'Use backticks: `Hello ${name}` for string interpolation.', 'hint' => 'backticks'],
            ['front' => 'What does async/await do?', 'back' => 'Provides syntactic sugar for working with Promises in a synchronous-looking manner.', 'hint' => 'promises'],
            ['front' => 'How do you import a module in ES6?', 'back' => 'import { func } from "./module.js"; or import func from "./module.js";', 'hint' => 'import keyword'],
            ['front' => 'What is the spread operator?', 'back' => 'The ... operator expands iterables like arrays into individual elements.', 'hint' => '...'],
            ['front' => 'What is a default parameter?', 'back' => 'function greet(name = "World") {} - provides a default value if argument is undefined.', 'hint' => '= default'],
            ['front' => 'How do you define a class in ES6?', 'back' => 'class MyClass { constructor() {} method() {} }', 'hint' => 'class keyword'],
            ['front' => 'What is the rest parameter?', 'back' => 'function sum(...args) {} - collects multiple arguments into an array.', 'hint' => '...args'],
            ['front' => 'What is Optional Chaining?', 'back' => 'obj?.property safely accesses nested properties without throwing if parent is null/undefined.', 'hint' => '?.'],
        ];
    }

    private function japaneseHiragana(): array
    {
        return [
            ['front' => 'あ (a)', 'back' => 'Pronounced "ah" - The first character of the Japanese syllabary.', 'hint' => 'First character'],
            ['front' => 'い (i)', 'back' => 'Pronounced "ee" - Sounds like the "ee" in "see".', 'hint' => 'Like "see"'],
            ['front' => 'う (u)', 'back' => 'Pronounced "oo" - Similar to the "oo" in "food" but shorter.', 'hint' => 'Like "food"'],
            ['front' => 'え (e)', 'back' => 'Pronounced "eh" - Similar to the "e" in "bet".', 'hint' => 'Like "bet"'],
            ['front' => 'お (o)', 'back' => 'Pronounced "oh" - Similar to the "o" in "home".', 'hint' => 'Like "home"'],
            ['front' => 'か (ka)', 'back' => 'Pronounced "kah" - The "k" sound plus "a".', 'hint' => 'k + a'],
            ['front' => 'き (ki)', 'back' => 'Pronounced "kee" - The "k" sound plus "i".', 'hint' => 'k + i'],
            ['front' => 'く (ku)', 'back' => 'Pronounced "koo" - The "k" sound plus "u".', 'hint' => 'k + u'],
            ['front' => 'け (ke)', 'back' => 'Pronounced "keh" - The "k" sound plus "e".', 'hint' => 'k + e'],
            ['front' => 'こ (ko)', 'back' => 'Pronounced "koh" - The "k" sound plus "o".', 'hint' => 'k + o'],
        ];
    }

    private function spanishFood(): array
    {
        return [
            ['front' => 'la manzana', 'back' => 'apple', 'hint' => 'red fruit'],
            ['front' => 'el pan', 'back' => 'bread', 'hint' => 'bakery'],
            ['front' => 'el agua', 'back' => 'water', 'hint' => 'essential drink'],
            ['front' => 'la leche', 'back' => 'milk', 'hint' => 'white dairy'],
            ['front' => 'el huevo', 'back' => 'egg', 'hint' => 'breakfast'],
            ['front' => 'el queso', 'back' => 'cheese', 'hint' => 'dairy product'],
            ['front' => 'la carne', 'back' => 'meat', 'hint' => 'protein'],
            ['front' => 'el pollo', 'back' => 'chicken', 'hint' => 'poultry'],
            ['front' => 'el pescado', 'back' => 'fish', 'hint' => 'seafood'],
            ['front' => 'la verdura', 'back' => 'vegetable', 'hint' => 'plant food'],
        ];
    }

    private function mysqlFundamentals(): array
    {
        return [
            ['front' => 'What is a Primary Key?', 'back' => 'A unique identifier for each record in a table. Must be unique and not null.', 'hint' => 'unique ID'],
            ['front' => 'What is a Foreign Key?', 'back' => 'A field that links to the Primary Key of another table, establishing relationships.', 'hint' => 'reference'],
            ['front' => 'What is an Index?', 'back' => 'A data structure that improves query speed on frequently searched columns.', 'hint' => 'speed boost'],
            ['front' => 'What is Normalization?', 'back' => 'Organizing data to reduce redundancy and improve data integrity.', 'hint' => 'reduce duplication'],
            ['front' => 'What is a JOIN operation?', 'back' => 'Combines rows from two or more tables based on related columns.', 'hint' => 'combine tables'],
            ['front' => 'What is the difference between INNER JOIN and LEFT JOIN?', 'back' => 'INNER JOIN returns only matching rows. LEFT JOIN returns all left table rows plus matching right rows.', 'hint' => 'matching vs all'],
            ['front' => 'What is a transaction?', 'back' => 'A sequence of operations treated as a single unit - all succeed or all fail.', 'hint' => 'all or nothing'],
            ['front' => 'What is GROUP BY used for?', 'back' => 'Groups rows that have the same values into summary rows.', 'hint' => 'aggregate'],
            ['front' => 'What is HAVING clause?', 'back' => 'Filters groups after GROUP BY, similar to WHERE but for aggregates.', 'hint' => 'filter groups'],
            ['front' => 'What is EXPLAIN?', 'back' => 'Shows how MySQL executes a query, useful for optimization.', 'hint' => 'query analysis'],
        ];
    }

    private function vueComponents(): array
    {
        return [
            ['front' => 'What is Vue Composition API?', 'back' => 'An alternative to Options API that uses imported functions like ref and computed for logic reuse.', 'hint' => 'setup()'],
            ['front' => 'What is a ref in Vue?', 'back' => 'Creates a reactive reference using ref() that must be accessed with .value in JS.', 'hint' => 'reactive()'],
            ['front' => 'What is computed property?', 'back' => 'A cached derived value that only recalculates when dependencies change.', 'hint' => 'cached'],
            ['front' => 'What is a watcher?', 'back' => 'watch() runs side effects when a reactive source changes.', 'hint' => 'watch changes'],
            ['front' => 'What are props?', 'back' => 'Custom attributes for passing data from parent to child components.', 'hint' => 'parent to child'],
            ['front' => 'What are emits?', 'back' => 'Custom events for sending data from child to parent components.', 'hint' => 'child to parent'],
            ['front' => 'What is a slot?', 'back' => 'Content distribution outlet in component templates for flexible composition.', 'hint' => 'content outlet'],
            ['front' => 'What is v-model?', 'back' => 'Two-way data binding syntax for form inputs and component values.', 'hint' => 'two-way binding'],
            ['front' => 'What are lifecycle hooks?', 'back' => 'Methods like onMounted and onUnmounted that run at specific component stages.', 'hint' => 'onMounted'],
            ['front' => 'What is provide/inject?', 'back' => 'Dependency injection pattern for passing data through the component tree without prop drilling.', 'hint' => 'dependency injection'],
        ];
    }

    private function pythonDataScience(): array
    {
        return [
            ['front' => 'What is a pandas DataFrame?', 'back' => 'A 2-dimensional labeled data structure like a spreadsheet with rows and columns.', 'hint' => '2D data'],
            ['front' => 'How do you read a CSV in pandas?', 'back' => 'pd.read_csv("file.csv") loads CSV data into a DataFrame.', 'hint' => 'read_csv'],
            ['front' => 'What is a numpy array?', 'back' => 'A homogeneous n-dimensional array for efficient numerical operations.', 'hint' => 'ndarray'],
            ['front' => 'How do you select a column in pandas?', 'back' => 'df["column_name"] or df.column_name returns a Series.', 'hint' => 'brackets or dot'],
            ['front' => 'What is iloc vs loc?', 'back' => 'iloc selects by integer position. loc selects by label/index.', 'hint' => 'position vs label'],
            ['front' => 'How do you filter rows in pandas?', 'back' => 'df[df["column"] > value] returns rows meeting the condition.', 'hint' => 'boolean indexing'],
            ['front' => 'What is groupby in pandas?', 'back' => 'Groups data by category values for aggregate operations.', 'hint' => 'split-apply-combine'],
            ['front' => 'How do you handle missing values?', 'back' => 'dropna() removes, fillna() replaces missing values.', 'hint' => 'drop or fill'],
            ['front' => 'What is merge in pandas?', 'back' => 'Combines DataFrames similar to SQL JOIN operations.', 'hint' => 'join'],
            ['front' => 'What is matplotlib?', 'back' => 'Python plotting library for creating static visualizations.', 'hint' => 'plotting'],
        ];
    }

    private function frenchVerbs(): array
    {
        return [
            ['front' => 'être (to be) - je ___', 'back' => 'je suis', 'hint' => 'first person singular'],
            ['front' => 'être (to be) - tu ___', 'back' => 'tu es', 'hint' => 'second person singular'],
            ['front' => 'être (to be) - il/elle ___', 'back' => 'il/elle est', 'hint' => 'third person singular'],
            ['front' => 'être (to be) - nous ___', 'back' => 'nous sommes', 'hint' => 'first person plural'],
            ['front' => 'avoir (to have) - j\'___', 'back' => 'j\'ai', 'hint' => 'first person singular'],
            ['front' => 'avoir (to have) - tu ___', 'back' => 'tu as', 'hint' => 'second person singular'],
            ['front' => 'avoir (to have) - il/elle ___', 'back' => 'il/elle a', 'hint' => 'third person singular'],
            ['front' => 'aller (to go) - je ___', 'back' => 'je vais', 'hint' => 'first person singular'],
            ['front' => 'faire (to do) - je ___', 'back' => 'je fais', 'hint' => 'first person singular'],
            ['front' => 'prendre (to take) - je ___', 'back' => 'je prends', 'hint' => 'first person singular'],
        ];
    }

    private function awsCloud(): array
    {
        return [
            ['front' => 'What is EC2?', 'back' => 'Elastic Compute Cloud - provides scalable virtual servers in the cloud.', 'hint' => 'virtual servers'],
            ['front' => 'What is S3?', 'back' => 'Simple Storage Service - object storage with unlimited scalability.', 'hint' => 'object storage'],
            ['front' => 'What is RDS?', 'back' => 'Relational Database Service - managed SQL databases.', 'hint' => 'managed database'],
            ['front' => 'What is Lambda?', 'back' => 'Serverless compute service that runs code in response to events.', 'hint' => 'serverless'],
            ['front' => 'What is CloudFront?', 'back' => 'Content Delivery Network (CDN) for fast content delivery.', 'hint' => 'CDN'],
            ['front' => 'What is VPC?', 'back' => 'Virtual Private Cloud - isolated network resources.', 'hint' => 'private network'],
            ['front' => 'What is IAM?', 'back' => 'Identity and Access Management - controls user access and permissions.', 'hint' => 'access control'],
            ['front' => 'What is Route 53?', 'back' => 'DNS web service - domain registration and routing.', 'hint' => 'DNS'],
            ['front' => 'What is Elastic Beanstalk?', 'back' => 'Platform as Service for easy application deployment.', 'hint' => 'PaaS'],
            ['front' => 'What is AWS availability zone?', 'back' => 'One or more data centers within an AWS region.', 'hint' => 'data center'],
        ];
    }

    private function dockerKubernetes(): array
    {
        return [
            ['front' => 'What is a Docker container?', 'back' => 'A lightweight, standalone executable package that includes everything to run an application.', 'hint' => 'package'],
            ['front' => 'What is a Dockerfile?', 'back' => 'A script containing instructions to build a Docker image.', 'hint' => 'build script'],
            ['front' => 'What is docker-compose?', 'back' => 'Tool for defining and running multi-container applications.', 'hint' => 'multi-container'],
            ['front' => 'What is a Kubernetes Pod?', 'back' => 'The smallest deployable unit - one or more containers sharing resources.', 'hint' => 'smallest unit'],
            ['front' => 'What is a Kubernetes Service?', 'back' => 'An abstraction for a group of pods providing network access.', 'hint' => 'network access'],
            ['front' => 'What is a Deployment in K8s?', 'back' => 'Manages pod replicas and updates - ensures desired state.', 'hint' => 'manages pods'],
            ['front' => 'What is a Namespace in K8s?', 'back' => 'Virtual cluster for organizing and isolating resources.', 'hint' => 'isolation'],
            ['front' => 'What is a ConfigMap?', 'back' => 'Stores configuration data separate from application code.', 'hint' => 'configuration'],
            ['front' => 'What is a Secret?', 'back' => 'Stores sensitive data like passwords and keys.', 'hint' => 'sensitive data'],
            ['front' => 'What is a Helm chart?', 'back' => 'A package manager template for Kubernetes applications.', 'hint' => 'package manager'],
        ];
    }

    private function personalNotes(): array
    {
        return [
            ['front' => 'Key concept: DRY Principle', 'back' => 'Don\'t Repeat Yourself - Avoid duplication of code and logic.', 'hint' => 'avoid repetition'],
            ['front' => 'Remember: KISS Principle', 'back' => 'Keep It Simple, Stupid - Simplicity should be a key goal.', 'hint' => 'keep simple'],
            ['front' => 'Note: Time complexity', 'back' => 'Big O notation: O(1) constant, O(log n) logarithmic, O(n) linear.', 'hint' => 'Big O'],
            ['front' => 'Reminder: Git commit convention', 'back' => 'Use present tense: "Add feature" not "Added feature".', 'hint' => 'present tense'],
            ['front' => 'Tip: Code review checklist', 'back' => 'Check for bugs, performance, security, readability.', 'hint' => 'review checklist'],
        ];
    }

    private function interviewPrep(): array
    {
        return [
            ['front' => 'Explain: Event Loop in JavaScript', 'back' => 'Monitors call stack and callback queue. Pushes callbacks to stack when empty.', 'hint' => 'async mechanism'],
            ['front' => 'Explain: CSS Box Model', 'back' => 'Content, padding, border, margin form the layered structure of elements.', 'hint' => 'element layers'],
            ['front' => 'Explain: REST vs GraphQL', 'back' => 'REST: multiple endpoints, fixed structure. GraphQL: single endpoint, flexible queries.', 'hint' => 'endpoints'],
            ['front' => 'Explain: SQL Injection', 'back' => 'Malicious SQL inserted via user input. Prevent with prepared statements.', 'hint' => 'security'],
            ['front' => 'Explain: Debounce vs Throttle', 'back' => 'Debounce: delay until pause. Throttle: limit execution frequency.', 'hint' => 'timing'],
        ];
    }

    private function legacyContent(): array
    {
        return [
            ['front' => 'Old technology note', 'back' => 'This content is archived for historical reference.', 'hint' => 'archived'],
        ];
    }

    private function defaultFlashcards(Deck $deck): array
    {
        return [
            ['front' => "What is the main concept of {$deck->title}?", 'back' => "This is a sample flashcard for {$deck->title}.", 'hint' => 'main concept'],
            ['front' => "Key term in {$deck->category}", 'back' => "Definition and explanation for this {$deck->category} term.", 'hint' => 'definition'],
            ['front' => "Important fact about {$deck->title}", 'back' => "An essential piece of information related to {$deck->title}.", 'hint' => 'important'],
        ];
    }
}
