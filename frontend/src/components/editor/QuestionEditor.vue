<template>
    <div class="flex items-center justify-between">
        <!-- displaying the quesitions-->
        <h3 class="text-lg font-bold">
            {{ index +1 }}. {{ model.question }}
        </h3>
        <!--end of displaying the questions-->

        <div class="flex items-center">
            <!-- add new quesion -->
            <button type="button" @click="addQuestion" class=
            "flex
            items-center
            text-xs
            py-1
            px-3
            mr-2
            rounded-sm
            text-white
            bg-gray-600
            hover:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>

            Add </button>
              <!--end of add new quesion -->

              <!--  delete quesion -->
              <button type="button" @click="deleteQuestion()" class=
                "flex
                items-center
                text-xs
                py-1
                px-3
                rounded-sm
                border border-transparent
                text-red-500
                hover:border-red-600
                ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                delete
            </button>
            <!--end of deletequesion -->
        </div>
    </div><!--this is the end of the question,add and delete button-->
    <div class="grid gap-3 grid-cols-12">
        <!-- question -->
        <div class="mt-3 col-span-9">
            <label :for="'question_text_' + model_data" class=
            "block
            text-sm
            font-medium
            text-gray-700
            ">Question Text
            </label>

            <input type="text" :name="'question_text_' + model_data"
            v-model="model.question"
            @change="dataChange"
            :id="'question_text_' +model_data"
            class=
            "mt-1
            focus:ring-indigo-500
            focus:border-indigo-500
            block
            w-full
            shadow-sm
            sm:text-sm
            border-gray-300
            rounded-md">
        </div>
            <!--/question -->

        <!--  Question Type -->
        <div class="mt-3 col-span-3">
            <label for="question_type" class=
                "block
                text-sm
                font-medium
                text-gray-700">
                Select Question Type
            </label>
            <select id="question_type"
            name="question_type"
            v-model="model.type"
            @change="typeChange"
            class=
            "mt-1
            block
            w-full
            py-2
            px-3
            border border-gray-300
            bg-white
            rounded-md
            shadow-sm
            focus:outline-none
            focus:ring-indigo-500
            focus:border-indigo-500
            sm:text-sm

            ">
                <option v-for="type in questionTypes" :key="type" :value="type" >
                {{ upperCaseFirst(type) }}
                </option>
            </select>
        </div>
        <!--end of question type -->
    </div>

    <!--Question description-->
    <div class="mt-3 col-span-9">
        <label :for="'question_description_' + model.id"
        class="block text-sm font-medium text-gray-700">Description</label>
        <textarea :name="'question_description_' + model.id" 
        v-model="model.description"
        @change="dataChange"
        :id="'question_description_' + model.id"
        class="mt-1
        focus:ring-indigo-500
        focus:border-indigo-500
        block
        w-full
        shadow-sm
        sm:text-sm
        border-gray-300
        rounded-md"></textarea>
    </div>
    <!--end of Question description-->

    <!--data-->
        <div>
            <div v-if="shouldHaveOptions()" class="mt-2">
                <h4 class="
                    text-sm
                    font-semibold 
                    mb-1
                    flex
                    justify-between 
                    items-center">options
                    <!--adding of options-->
                    <button type="button" @click="addOptions()" class="
                        flex
                        items-center
                        text-xs
                        py-1
                        px-2
                        rounded-sm
                        text-white
                        bg-gray-600
                        hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                        </svg>
                        Add Options
                    </button>
                    <!--ending of options-->
                </h4>

                <!--display if it does not have options-->
                <div v-if="!model.data.options.length" class="
                    text-xs
                    text-gray-600 
                    text-center py-3">
                    You don't have any option defined
                </div>
                <!--end of displaying options-->

                <!--option list-->
                    <div v-for="(option,index) in model.data.options" :key="option.uuid" 
                    class="flex item-center mb-1">
                        <span class="w-6 text-sm">{{ index + 1 }}. </span>
                        <input type="text" v-model="option.text"
                        @change="dataChange" class="
                        w-full
                        rounded-sm
                        py-1
                        px-2 
                        text-xs
                        border border-gray-300
                        focus:border-indigo-500"/>

                        <!--delete options-->
                            <button type="button" @click="removeOption(option)" class="
                                h-6
                                w-6
                                rounded-full
                                flex
                                items-center
                                justify-center
                                text-red-500
                                border border-transparent 
                                transitions-colors
                                hover:border-red-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>

                            </button>
                         <!--delete options-->
                    </div>
                <!-- /option list-->
            </div>
        </div>
    <!--end of data-->
    

</template>

<script setup>
import {ref,computed} from 'vue'
import {v4 as uuidv4} from "uuid"
import store from '../../store'


const props = defineProps({
    question:Object,
    index:Number

})

const emit =defineEmits(["change","deleteQuesion","addQuestion"])
 
//re-create the whole question to avoid  uninetentional reference change 
 const model =  ref(JSON.parse(JSON.stringify(props.question))) //this is going into the survey.views to fetch the questions
// get question type from vue-x
const questionTypes = computed(()=> store.state.questionTypes)

function upperCaseFirst(str){
    return str.charAt(0).toUpperCase() + str.slice(1)
}
//check if questions should have options
function shouldHaveOptions(){
    return["select", "radio","checkbox"].includes(model.value.type)
}

//function to get options 
function getOptions(){
    return model.value.data.options
}
//set options
function setOptions(options){
    model.value.data.options = options;
}

//addOptions
function addOptions(){
    setOptions([
        ...getOptions(),
        {uuid: uuidv4(), text:""}
    ]);
    dataChange();
}

//removeOption
function removeOption(op){
    setOptions(getOptions().filter((opt) => opt !== op))
    dataChange()
}

//tochange option type
function typeChange(){
    if(shouldHaveOptions()) {
        setOptions(getOptions() || [])
    }
    dataChange()
}

//Emit the data change 
function dataChange() {
    const data = Json.parse(Json.strigify(model.value))
    if(!shouldHaveOptions()) {
        delete data.data.options
    }
    emit("change",data)
}

function addQuestion(){
    emit("addQuestion", props.index + 1)
}
function deleteQuestion(){
    emit("deleteQuestion", props.question)
}

</script>