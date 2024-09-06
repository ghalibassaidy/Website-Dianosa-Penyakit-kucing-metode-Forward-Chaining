import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.SwingConstants;

public class Main {
    public static void main(String[] args) {
        // Create a new JFrame container
        JFrame frame = new JFrame("Arap Gecko");

        // Set the frame's default close operation
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

        // Create a new JLabel with the text "Arap Gecko"
        JLabel label = new JLabel("Arap Gecko", SwingConstants.CENTER);

        // Add the label to the frame's content pane
        frame.getContentPane().add(label);

        // Set the size of the frame
        frame.setSize(300, 200);

        // Make the frame visible on the screen
        frame.setVisible(true);
    }
}