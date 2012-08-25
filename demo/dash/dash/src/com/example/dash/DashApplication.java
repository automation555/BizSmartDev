package com.example.dash;

import com.vaadin.Application;
import com.vaadin.ui.*;

public class DashApplication extends Application {
	/**
	 * 
	 */
	private static final long serialVersionUID = 2925410098338058137L;

	@Override
	public void init() {
		Window mainWindow = new Window("Dash Application");
		Label label = new Label("Hello Vaadin user");
		mainWindow.addComponent(label);
		setMainWindow(mainWindow);
	}

}
