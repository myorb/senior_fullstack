import { shallowMount, mount } from "@vue/test-utils";
import App from "./App.vue";

function getMountedComponent(Component, propsData) {
  return mount(Component, {
    propsData,
  });
}

const wrapper = getMountedComponent(App, {
  hotelId: "123-test-hotel",
});

describe("Testing <App>", () => {
  it("should have a <button> with the properly text", () => {
    expect(wrapper.find("button").text()).toEqual("SUBMIT");
  });

  it("should have a <button> disabled prop", () => {
    expect(wrapper.find("button").attributes("disabled")).toEqual("disabled");
  });

  it("should change score on <imput> click", () => {
    wrapper
      .findAll("input")
      .at(3)
      .trigger("click");

    expect(wrapper.vm.score).toEqual("4");
  });

  it("should have hotelId", () => {
    expect(wrapper.vm.hotelId).toEqual("123-test-hotel");
  });
  //  ...
});
